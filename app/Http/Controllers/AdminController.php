<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function ensureAdmin()
    {
        $user = Auth::user();
        if (!$user || ($user->Role ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        $students = Student::with('UserAccount')->get();
        $teachers = Teacher::with('userAccount')->get();
        return view('admin.index', compact('students', 'teachers'));
    }

    public function createStudent()
    {
        $this->ensureAdmin();
        return view('admin.create_student');
    }

    public function storeStudent(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Email' => 'required|email',
            'Contactno' => 'nullable|string',
            'degree_id' => 'nullable|integer',
            'username' => 'required|string|unique:user_accounts,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = UserAccount::create([
            'username' => $data['username'],
            'email' => $data['Email'] ?? null,
            'Password' => Hash::make($data['password']),
            'Role' => 'student',
            'is_active' => 1,
        ]);

        Student::create([
            'Fname' => $data['Fname'],
            'Mname' => $data['Mname'] ?? null,
            'Lname' => $data['Lname'],
            'Address' => $request->input('Address'),
            'Email' => $data['Email'],
            'Contactno' => $data['Contactno'] ?? null,
            'degree_id' => $data['degree_id'] ?? null,
            'user_account_id' => $user->id,
        ]);

        return redirect()->route('admin.index')->with('status', 'Student account created.');
    }

    public function createTeacher()
    {
        $this->ensureAdmin();
        return view('admin.create_teacher');
    }

    public function storeTeacher(Request $request)
    {
        $this->ensureAdmin();

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

        Teacher::create([
            'user_account_id' => $user->id,
        ]);

        return redirect()->route('admin.index')->with('status', 'Teacher account created.');
    }
}
