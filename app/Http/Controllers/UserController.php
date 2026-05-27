<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function showLoginForm()
    {
        return view('loginPage');
    }

    public function showLoginSuccess()
    {
        $user = Auth::user();
        $redirectUrl = route('students');

        if ($user) {
            $role = $user->Role ?? 'student';

            if ($role === 'admin') {
                $redirectUrl = route('admin.index');
            } elseif ($role === 'teacher') {
                $redirectUrl = route('teacher.dashboard');
            } else {
                $redirectUrl = route('student.dashboard');
            }
        }

        return view('loginSuccess', [
            'redirectUrl' => $redirectUrl,
        ]);
    }

    public function showForgotPasswordForm()
    {
        return view('forgotPassword');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $userName = $request->input('username');
        $plainPassword = $request->input('password');

        // Find by username OR email
        $user = UserAccount::where('username', $userName)
            ->orWhere('email', $userName)
            ->first();

        if (!$user) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid username/email or password']);
        }

        $storedPassword = (string) ($user->Password ?? '');
        $isPlaintextMatch = hash_equals($storedPassword, $plainPassword);

        if (!$this->passwordMatches($plainPassword, $storedPassword)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid username/email or password']);
        }

        // Check active status
        if (property_exists($user, 'is_active') && !(bool) $user->is_active) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Your account is inactive. Please contact administrator.']);
        }

        if ($isPlaintextMatch || Hash::needsRehash($storedPassword)) {
            $user->Password = Hash::make($plainPassword);
            $user->save();
        }

        // Manually authenticate the user (respect "remember me")
        $remember = $request->boolean('remember');
        Auth::loginUsingId($user->id, $remember);

        // Redirect based on role
        $role = $user->Role ?? 'student';
        if ($role === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } else {
            // For students: only force change-password if account still has a temp password flag
            $isTemp = (bool) ($user->is_temp_password ?? false);
            if ($isTemp) {
                return redirect()->route('student.change-password.show');
            }

            return redirect()->route('student.dashboard');
        }
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Logged out successfully.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'email' => ['required', 'email'],
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = UserAccount::where('username', $request->input('username'))
            ->where('email', $request->input('email'))
            ->first();

        if (!$user) {
            return back()
                ->withInput($request->only('username', 'email'))
                ->withErrors(['account' => 'Walang tumugmang account para sa username at email na ito.']);
        }

        $storedPassword = (string) ($user->Password ?? '');
        $providedCurrentPassword = (string) $request->input('current_password');

        if (!$this->passwordMatches($providedCurrentPassword, $storedPassword)) {
            return back()
                ->withInput($request->only('username', 'email'))
                ->withErrors(['current_password' => 'Mali ang current password.']);
        }

        $user->Password = Hash::make($request->input('password'));
        $user->save();

        return redirect()
            ->route('login')
            ->with('status', 'Password updated na. Maaari ka nang mag-login.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function passwordMatches(string $plainPassword, string $storedPassword): bool
    {
        if ($storedPassword === '') {
            return false;
        }

        try {
            if (Hash::check($plainPassword, $storedPassword)) {
                return true;
            }
        } catch (RuntimeException $e) {
            // Fall back to legacy hash/plaintext handling below.
        }

        if (password_verify($plainPassword, $storedPassword)) {
            return true;
        }

        return hash_equals($storedPassword, $plainPassword);
    }
}

