<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Degree;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
class StudentController extends Controller
{
    /**
     * Display the students page with a list of students
     */
     public function index(Request $request)
    {
        // If request expects JSON (AJAX), return full list for client-side rendering
        if ($request->wantsJson() || $request->ajax()) {
            $students = Student::with('degree')->orderByDesc('created_at')->orderBy('id')->get();
            return response()->json($students);
        }

        $student = Student::paginate(10);
        return view('students')->with('students', $student);
    }

    // public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     $students = Student::with('degree')->when($search, function ($query) use ($search) {
    //         $query->where('Fname', 'like', "%$search%")
    //               ->orWhere('Mname', 'like', "%$search%")
    //               ->orWhere('Lname', 'like', "%$search%")
    //               ->orWhere('Address', 'like', "%$search%")
    //               ->orWhere('Email', 'like', "%$search%")
    //               ->orWhere('Contactno', 'like', "%$search%")
    //               ->orWhereHas('degree', function ($degreeQuery) use ($search) {
    //                   $degreeQuery->where('name', 'like', "%$search%");
    //               });
    //     })->paginate(5)->withQueryString();

    //     $degrees = Degree::orderBy('id')->get();

    //     return view('students', compact('students', 'degrees', 'search'));
    // }

    /**
     * Store a new student record in the database
     */
    // public function store(Request $request)
    // {
    //     $student = new Student();
    //     $student->Fname = $request->Fname;
    //     $student->Mname = $request->Mname;
    //     $student->Lname = $request->Lname;
    //     $student->Email = $request->Email;
    //     $student->Contactno = $request->Contactno;
    //     $student->save();

    //     return redirect()->route('students')->with('success', 'Successfully');
    // }

    /**
     * Display add student page
     */
    public function create()
    {
        $degrees = Degree::orderBy('name')->get();

        return view('addstudent', compact('degrees'));
    }

    /**
     * Display the home page
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }

    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'Fname' => 'required|min:2',
        //    // 'Mname' => ['nullable', 'string', 'max:255'],
        //     'Lname' => 'required|min:2',
        //     'Address' => 'required|string|max:255',
        //     'Email' => 'required|email|unique:students,Email',
        //     'Contactno' => 'required|digits:11',
        //     'degree_id' => 'required',
    
        $validator = Validator::make($request->all(), [
            'Fname' => 'required|min:2',
            'Mname' => ['nullable', 'string', 'max:255'],
            'Lname' => 'required|min:2',
            'Address' => 'required|string|max:255',
            'Email' => 'required|email|unique:user_accounts,email',
            'username' => 'nullable|string|max:255|unique:user_accounts,username',
            'Password' => 'required|min:8',
            'Contactno' => 'required|digits:11',
            'degree_id' => 'required|exists:degrees,id',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $username = trim((string) $request->input('username'));

        if ($username === '') {
            $baseUsername = Str::slug($request->input('Fname') . '.' . $request->input('Lname'), '.');
            if ($baseUsername === '') {
                $baseUsername = 'student';
            }

            $username = $baseUsername;
            $counter = 1;
            while (UserAccount::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
        }

        $user = UserAccount::create([
            'username' => $username,
            'email' => $request->input('Email'),
            'Password' => Hash::make($request->input('Password')),
            'Role' => 'student',
            'is_active' => 1,
        ]);

        $studentData = [
            'user_account_id' => $user->id,
            'Fname' => $request->input('Fname'),
            'Mname' => $request->input('Mname'),
            'Lname' => $request->input('Lname'),
            'Address' => $request->input('Address'),
            'Contactno' => $request->input('Contactno'),
            'degree_id' => $request->input('degree_id'),
        ];

        if (Schema::hasColumn('students', 'Email')) {
            $studentData['Email'] = $request->input('Email');
        }

          $student = Student::create($studentData);

      $msg = "Student added successfully";
        Log::info($msg);
        Log::notice ($msg);
        Log::warning ($msg);
        Log::error ($msg);
        Log::critical ($msg);
        Log::alert ($msg);
        Log::emergency ($msg);
    
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => 'created',
                'model' => 'student',
                'record' => [
                    'id' => $student->id,
                    'username' => optional($student->UserAccount)->username,
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

        return redirect()->route('students')->with('message', 'Student added successfully.');
    }

    public function show(string $id)
    {
        $student = Student::with('degree')->findOrFail($id);

        return view('StudentDetails', compact('student'));
    }

    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        $degrees = Degree::orderBy('name')->get();

        return view('editstudent', compact('student', 'degrees'));
    }

    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);
        $before = $student->only(['Fname', 'Mname', 'Lname', 'Address', 'Email', 'Contactno', 'degree_id']);

        $validated = $request->validate([
            'Fname' => ['required', 'string', 'max:255'],
            'Mname' => ['nullable', 'string', 'max:255'],
            'Lname' => ['required', 'string', 'max:255'],
            'Address' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'email', 'max:255'],
            'Contactno' => ['required', 'string', 'max:50'],
            'degree_id' => ['required', 'exists:degrees,id'],
        ]);

        $student->update($validated);

        $student->refresh();
        $after = $student->only(['Fname', 'Mname', 'Lname', 'Address', 'Email', 'Contactno', 'degree_id']);
        $changes = [];

        foreach (array_keys($validated) as $field) {
            if (($before[$field] ?? null) != ($after[$field] ?? null)) {
                $changes[$field] = [
                    'old' => $before[$field] ?? null,
                    'new' => $after[$field] ?? null,
                ];
            }
        }

        ActivityLog::create([
            'action' => 'EDIT',
            'subject_type' => 'Student',
            'record_id' => $student->id,
            'description' => 'Edited student: ' . trim($student->Fname . ' ' . $student->Lname),
            'changes' => $changes,
        ]);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'action' => 'updated',
                'model' => 'student',
                'record' => [
                    'id' => $student->id,
                    'username' => optional($student->UserAccount)->username,
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

        return redirect()->route('students')->with('success', 'Student updated successfully.');
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $before = $student->only(['Fname', 'Mname', 'Lname', 'Address', 'Email', 'Contactno', 'degree_id']);
        $student->delete();

        ActivityLog::create([
            'action' => 'DELETE',
            'subject_type' => 'Student',
            'record_id' => $id,
            'description' => 'Deleted student: ' . trim(($before['Fname'] ?? '') . ' ' . ($before['Lname'] ?? '')),
            'changes' => [
                'record' => [
                    'old' => $before,
                    'new' => null,
                ],
            ],
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'student', 'record' => ['id' => $id]]);
        }

        return redirect()->route('students')->with('success', 'Student deleted successfully.');
    }

    /**
     * Display the student dashboard
     */
    public function dashboard()
    {
        $userAccount = auth()->user();
        
        if (!$userAccount) {
            return redirect()->route('login');
        }

        if ((bool) ($userAccount->is_temp_password ?? false)) {
            return redirect()->route('student.change-password.show');
        }

        $student = Student::with('degree')->where('user_account_id', $userAccount->id)->firstOrFail();

        return view('studentDashboard', compact('student'));
    }

    /**
     * Show the change password form for first-time login
     */
    public function showChangePasswordForm()
    {
        return view('changePassword');
    }

    /**
     * Store the new password from the change password form
     */
    public function storeChangePassword(Request $request)
    {
        return $this->processPasswordUpdate($request, true);
    }

    /**
     * Update password from the dashboard modal
     */
    public function updatePassword(Request $request)
    {
        return $this->processPasswordUpdate($request, false);
    }

    private function processPasswordUpdate(Request $request, bool $clearTempPassword)
    {
        $userAccount = auth()->user();

        if (!$userAccount) {
            return redirect()->route('login');
        }

        $throttleKey = 'change-password:' . $userAccount->id . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'current_password' => "Masyado nang maraming attempts. Subukan ulit pagkalipas ng {$seconds} segundo.",
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|different:current_password|confirmed',
                'new_password_confirmation' => 'required|string',
            ],
            [
                'current_password.required' => 'Kailangan ang current password.',
                'new_password.required' => 'Kailangan ang new password.',
                'new_password.min' => 'Ang new password ay dapat may minimum na 6 characters.',
                'new_password.confirmed' => 'Hindi tugma ang new password at confirm password.',
                'new_password.different' => 'Ang bagong password ay dapat na iba sa current password.',
                'new_password_confirmation.required' => 'Kailangan ang confirm password.',
            ]
        );

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey, 5);

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!Hash::check($request->input('current_password'), (string) $userAccount->Password)) {
            RateLimiter::hit($throttleKey, 5);

            return back()
                ->withErrors(['current_password' => 'Ang current password ay hindi tama.'])
                ->withInput();
        }

        RateLimiter::clear($throttleKey);

        $userAccount->Password = Hash::make($request->input('new_password'));

        if ($clearTempPassword) {
            $userAccount->is_temp_password = false;
        }

        $userAccount->save();

        return redirect()->route('student.dashboard')->with('success', 'Password changed successfully!');
    }

}


