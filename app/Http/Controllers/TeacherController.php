<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccount;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Degree;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Exports\TeachersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function ensureTeacher()
    {
        $user = Auth::user();
        if (($user->Role ?? 'teacher') !== 'teacher') {
            abort(403, 'Unauthorized');
        }
    }

    public function dashboard()
    {
        $this->ensureTeacher();
        $user = Auth::user();

        return view('teacher.dashboard', [
            'teacher' => $user,
        ]);
    }

    public function management()
    {
        $this->ensureTeacher();
        $search = request()->input('search');

        $students = Student::with('UserAccount', 'degree')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('Fname', 'like', "%{$search}%")
                      ->orWhere('Lname', 'like', "%{$search}%")
                      ->orWhere('Email', 'like', "%{$search}%")
                      ->orWhere('Contactno', 'like', "%{$search}%");
                })->orWhereHas('degree', function ($dq) use ($search) {
                    $dq->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('Lname')
            ->paginate(10)
            ->withQueryString();

        $teachers = UserAccount::where('Role', 'teacher')->with('teacher')->get();
        return view('teacher.management', compact('students', 'teachers'));
    }

    public function degrees()
    {
        $this->ensureTeacher();
        $degrees = Degree::all();
        return view('teacher.degrees', compact('degrees'));
    }

    // Teacher-scoped degree store
    public function storeDegree(Request $request)
    {
        $this->ensureTeacher();

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:degrees,name',
        ]);

        $degree = Degree::create(['name' => $data['name']]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'action' => 'created', 'model' => 'degree', 'record' => ['id' => $degree->id, 'name' => $degree->name]]);
        }

        return redirect()->route('teacher.degrees')->with('success', 'Degree added successfully.');
    }

    // Teacher-scoped degree update
    public function updateDegree(Request $request, $id)
    {
        $this->ensureTeacher();

        $degree = Degree::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:degrees,name,' . $degree->id,
        ]);

        $degree->name = $data['name'];
        $degree->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'action' => 'updated', 'model' => 'degree', 'record' => ['id' => $degree->id, 'name' => $degree->name]]);
        }

        return redirect()->route('teacher.degrees')->with('success', 'Degree updated successfully.');
    }

    // Teacher-scoped degree destroy
    public function destroyDegree($id)
    {
        $this->ensureTeacher();

        $degree = Degree::findOrFail($id);
        $degree->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'degree', 'record' => ['id' => $degree->id]]);
        }

        return redirect()->route('teacher.degrees')->with('success', 'Degree deleted successfully.');
    }

    public function activityLogs()
    {
        $this->ensureTeacher();
        $logs = ActivityLog::latest()->paginate(20);
        return view('teacher.activity-logs', compact('logs'));
    }

    public function showChangePasswordForm()
    {
        $this->ensureTeacher();
        return view('teacher.change-password');
    }

    public function createStudent()
    {
        $this->ensureTeacher();
        $degrees = Degree::all();
        return view('teacher.create_student', compact('degrees'));
    }

    public function storeStudent(Request $request)
    {
        $this->ensureTeacher();

        $data = $request->validate([
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Address' => 'required|string|max:255',
            'degree_id' => 'required|exists:degrees,id',
            'Contactno' => 'required|string|max:20',
            'username' => 'required|string|unique:user_accounts,username',
            'password' => 'required|string|min:6|confirmed',
            'Email' => 'required|email',
        ]);

        $user = UserAccount::create([
            'username' => $data['username'],
            'email' => $data['Email'],
            'Password' => Hash::make($data['password']),
            'Role' => 'student',
            'is_active' => 1,
        ]);

        $student = Student::create([
            'Fname' => $data['Fname'],
            'Mname' => $data['Mname'],
            'Lname' => $data['Lname'],
            'Address' => $data['Address'],
            'Email' => $data['Email'],
            'Contactno' => $data['Contactno'],
            'degree_id' => $data['degree_id'],
            'user_account_id' => $user->id,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            $student->load('degree');
            return response()->json([
                'success' => true,
                'action' => 'created',
                'model' => 'student',
                'record' => [
                    'id' => $student->id,
                    'username' => $user->username,
                    'Email' => $student->Email,
                    'Fname' => $student->Fname,
                    'Mname' => $student->Mname,
                    'Lname' => $student->Lname,
                    'Address' => $student->Address,
                    'Contactno' => $student->Contactno,
                    'degree_name' => optional($student->degree)->name,
                    'created_at' => $student->created_at ? $student->created_at->format('M d, Y') : null,
                ],
            ]);
        }

        return redirect()->route('teacher.management')->with('status', 'Student created successfully.');
    }

    /**
     * Show a single student (teacher-scoped)
     */
    public function showStudent($id)
    {
        $this->ensureTeacher();

        $student = Student::with('UserAccount', 'degree')->findOrFail($id);

        return view('teacher.student_show', compact('student'));
    }

    /**
     * Update student (teacher-scoped)
     */
    public function updateStudent(Request $request, $id)
    {
        $this->ensureTeacher();

        $student = Student::findOrFail($id);

        $data = $request->validate([
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Address' => 'nullable|string|max:255',
            'Contactno' => 'nullable|string|max:50',
            'Email' => 'nullable|email',
            'username' => 'nullable|string|unique:user_accounts,username,' . ($student->user_account_id ?? 'NULL'),
        ]);

        // Update student fields
        $student->Fname = $data['Fname'];
        $student->Mname = $data['Mname'] ?? $student->Mname;
        $student->Lname = $data['Lname'];
        if (array_key_exists('Address', $data)) $student->Address = $data['Address'];
        if (array_key_exists('Contactno', $data)) $student->Contactno = $data['Contactno'];
        if (array_key_exists('Email', $data)) $student->Email = $data['Email'];
        $student->save();

        // Update linked user account (username/email)
        if ($student->user_account_id) {
            $ua = UserAccount::find($student->user_account_id);
            if ($ua) {
                if (!empty($data['username'])) $ua->username = $data['username'];
                if (!empty($data['Email'])) $ua->email = $data['Email'];
                $ua->save();
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            $student->load('degree');
            $ua = $student->user_account_id ? UserAccount::find($student->user_account_id) : null;
            return response()->json([
                'success' => true,
                'action' => 'updated',
                'model' => 'student',
                'record' => [
                    'id' => $student->id,
                    'username' => $ua ? $ua->username : null,
                    'Email' => $student->Email,
                    'Fname' => $student->Fname,
                    'Mname' => $student->Mname,
                    'Lname' => $student->Lname,
                    'Address' => $student->Address,
                    'Contactno' => $student->Contactno,
                    'degree_name' => optional($student->degree)->name,
                    'created_at' => $student->created_at ? $student->created_at->format('M d, Y') : null,
                ],
            ]);
        }

        return redirect()->route('teacher.management')->with('status', 'Student updated successfully.');
    }

    /**
     * Destroy student (teacher-scoped)
     */
    public function destroyStudent($id)
    {
        $this->ensureTeacher();

        $student = Student::findOrFail($id);

        // delete linked user account first (cascade may be present)
        try {
            if ($student->user_account_id) {
                $ua = UserAccount::find($student->user_account_id);
                if ($ua) $ua->delete();
            }
        } catch (\Exception $e) {
            // ignore
        }

        $student->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'student', 'record' => ['id' => $student->id]]);
        }

        return redirect()->route('teacher.management')->with('status', 'Student deleted successfully.');
    }

    public function createTeacher()
    {
        $this->ensureTeacher();
        return view('teacher.create_teacher');
    }

    public function storeTeacher(Request $request)
    {
        $this->ensureTeacher();

        $data = $request->validate([
            'username' => 'required|string|unique:user_accounts,username',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = UserAccount::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'Password' => Hash::make($data['password']),
            'Role' => 'teacher',
            'is_active' => 1,
        ]);

        $teacher = Teacher::create([
            'user_account_id' => $user->id,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => 'created',
                'model' => 'teacher',
                'record' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : null,
                ],
            ]);
        }

        return redirect()->route('teacher.management')->with('status', 'Teacher created successfully.');
    }

    public function editTeacher($id)
    {
        $this->ensureTeacher();

        $user = UserAccount::findOrFail($id);

        return view('teacher.edit_teacher', compact('user'));
    }

    public function updateTeacher(Request $request, $id)
    {
        $this->ensureTeacher();

        $user = UserAccount::findOrFail($id);

        $data = $request->validate([
            'username' => 'required|string|unique:user_accounts,username,' . $user->id,
            'email' => 'required|email',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $data['username'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->Password = Hash::make($data['password']);
        }

        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => 'updated',
                'model' => 'teacher',
                'record' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => $user->created_at ? $user->created_at->format('M d, Y') : null,
                ],
            ]);
        }

        return redirect()->route('teacher.management')->with('status', 'Teacher updated successfully.');
    }

    public function destroyTeacher($id)
    {
        $this->ensureTeacher();

        $user = UserAccount::findOrFail($id);

        // Remove teacher record if exists
        try {
            $teacher = Teacher::where('user_account_id', $user->id)->first();
            if ($teacher) {
                $teacher->delete();
            }
        } catch (\Exception $e) {
            // ignore and continue with deleting user account
        }

        $user->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'teacher', 'record' => ['id' => $user->id]]);
        }

        return redirect()->route('teacher.management')->with('status', 'Teacher deleted successfully.');
    }

    /**
     * Export students as Excel
     */
    public function exportStudentsExcel()
    {
        $this->ensureTeacher();
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    /**
     * Export students as PDF
     */
    public function exportStudentsPdf()
    {
        $this->ensureTeacher();
        $students = Student::with('UserAccount', 'degree')->get();
        $pdf = Pdf::loadView('teacher.exports.students_pdf', compact('students'));
        return $pdf->download('students.pdf');
    }

    /**
     * Export teachers as Excel
     */
    public function exportTeachersExcel()
    {
        $this->ensureTeacher();
        return Excel::download(new TeachersExport, 'teachers.xlsx');
    }

    /**
     * Export teachers as PDF
     */
    public function exportTeachersPdf()
    {
        $this->ensureTeacher();
        $teachers = UserAccount::where('Role', 'teacher')->get();
        $pdf = Pdf::loadView('teacher.exports.teachers_pdf', compact('teachers'));
        return $pdf->download('teachers.pdf');
    }

    public function updatePassword(Request $request)
    {
        $this->ensureTeacher();

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->Password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('teacher.dashboard')->with('status', 'Password updated.');
    }
}
