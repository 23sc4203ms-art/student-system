<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('students');
// });
Route::get('/', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/login/success', [UserController::class, 'showLoginSuccess'])->name('login.success');
Route::get('/forgot-password', [UserController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [UserController::class, 'resetPassword'])->name('forgot.password.submit');

// Student Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/change-password', [StudentController::class, 'showChangePasswordForm'])->name('student.change-password.show');
    Route::post('/student/change-password', [StudentController::class, 'storeChangePassword'])->name('student.change-password.store');
    Route::put('/student/change-password', [StudentController::class, 'updatePassword'])->name('student.change-password.update');
});

// Teacher Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/management', [TeacherController::class, 'management'])->name('teacher.management');
    Route::get('/teacher/management/students/create', [TeacherController::class, 'createStudent'])->name('teacher.students.create');
    Route::post('/teacher/management/students', [TeacherController::class, 'storeStudent'])->name('teacher.students.store');
    Route::get('/teacher/management/teachers/create', [TeacherController::class, 'createTeacher'])->name('teacher.teachers.create');
    Route::post('/teacher/management/teachers', [TeacherController::class, 'storeTeacher'])->name('teacher.teachers.store');
    Route::get('/teacher/degrees', [TeacherController::class, 'degrees'])->name('teacher.degrees');
    // Teacher-scoped degree management
    Route::post('/teacher/management/degrees', [TeacherController::class, 'storeDegree'])->name('teacher.degrees.store');
    Route::put('/teacher/management/degrees/{id}', [TeacherController::class, 'updateDegree'])->name('teacher.degrees.update');
    Route::delete('/teacher/management/degrees/{id}', [TeacherController::class, 'destroyDegree'])->name('teacher.degrees.destroy');
    Route::get('/teacher/activity-logs', [TeacherController::class, 'activityLogs'])->name('teacher.activity-logs');
    // Teacher-scoped student actions (view/update/delete) to keep actions under teacher routes
    Route::get('/teacher/management/students/{id}', [TeacherController::class, 'showStudent'])->name('teacher.students.show');
    Route::put('/teacher/management/students/{id}', [TeacherController::class, 'updateStudent'])->name('teacher.students.update');
    Route::delete('/teacher/management/students/{id}', [TeacherController::class, 'destroyStudent'])->name('teacher.students.destroy');
    // Export endpoints (Excel / PDF)
    Route::get('/teacher/management/export/students/excel', [TeacherController::class, 'exportStudentsExcel'])->name('teacher.export.students.excel');
    Route::get('/teacher/management/export/students/pdf', [TeacherController::class, 'exportStudentsPdf'])->name('teacher.export.students.pdf');
    Route::get('/teacher/management/export/teachers/excel', [TeacherController::class, 'exportTeachersExcel'])->name('teacher.export.teachers.excel');
    Route::get('/teacher/management/export/teachers/pdf', [TeacherController::class, 'exportTeachersPdf'])->name('teacher.export.teachers.pdf');
    Route::get('/teacher/change-password', [TeacherController::class, 'showChangePasswordForm'])->name('teacher.change-password.show');
    Route::put('/teacher/change-password', [TeacherController::class, 'updatePassword'])->name('teacher.change-password.update');
    // Teacher account management (edit / update / destroy)
    Route::get('/teacher/teachers/{id}/edit', [TeacherController::class, 'editTeacher'])->name('teacher.teachers.edit');
    Route::put('/teacher/teachers/{id}', [TeacherController::class, 'updateTeacher'])->name('teacher.teachers.update');
    Route::delete('/teacher/teachers/{id}', [TeacherController::class, 'destroyTeacher'])->name('teacher.teachers.destroy');
});

Route::get('/teacher', function () {
    return redirect()->route('teacher.dashboard');
})->middleware('auth');


// MVC Activity Routes
Route::get('/home', [StudentController::class, 'home'])->name('home');
Route::get('/about', [StudentController::class, 'about'])->name('about');

$studentUri = trim((string) env('STUDENT_URI', 'student'), '/');

Route::middleware(\App\Http\Middleware\DownForMaintenanceMW::class)->prefix($studentUri)->group(function () {
Route::get('/', [StudentController::class, 'index'])->name('students');
Route::post('/', [StudentController::class, 'store'])->name('students.store');
Route::get('/create', [StudentController::class, 'create'])->name('students.create');
Route::get('/{id}', [StudentController::class, 'show'])->name('students.show');
Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});

if ($studentUri !== 'student') {
	Route::redirect('/student', '/'.$studentUri);
}

Route::get('/degree', [DegreeController::class, 'index'])->name('degrees.index');
Route::get('/degree/create', [DegreeController::class, 'create'])->name('degrees.create');
Route::post('/degree', [DegreeController::class, 'store'])->name('degrees.store');
Route::get('/degree/{id}', [DegreeController::class, 'show'])->name('degrees.show');
Route::get('/degree/{id}/edit', [DegreeController::class, 'edit'])->name('degrees.edit');
Route::put('/degree/{id}', [DegreeController::class, 'update'])->name('degrees.update');
Route::post('/degree/{id}', [DegreeController::class, 'update'])->name('degrees.update.post');
Route::delete('/degree/{id}', [DegreeController::class, 'destroy'])->name('degrees.destroy');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/enrollment', [CourseController::class, 'enrollmentForm'])->name('enrollment.create');
Route::post('/enrollment', [CourseController::class, 'enrollStudent'])->name('enrollment.store');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
// AJAX endpoints for posts (used by client-side jQuery)
Route::get('/posts/list', [PostController::class, 'list'])->name('posts.list');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::get('/students/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
Route::get('/students/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/students/create', [AdminController::class, 'createStudent'])->name('admin.students.create');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
    Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('admin.teachers.create');
    Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');
});

// Legacy compatibility paths


Route::get('/greetings', [ClientController::class, 'DisplayGreetings']);
Route::get('/profile', [ClientController::class, 'DisplayProfile']);
Route::get('/dashboard', [ClientController::class, 'DisplayDashboard']);
Route::get('/aboutus', [ClientController::class, 'DisplayAboutUs']);
Route::resource('client', ClientController::class);

Route::get('/user_profile', [PagesController::class, 'userProfile']);
Route::get('/user_posts', [PagesController::class, 'userPosts']);
Route::get('/student_courses', [PagesController::class, 'studentCourses']);

Route::get('/maintenance',[PagesController::class, 'maintenance'])->name('Maintenance');

Route::middleware('group_middleware')->group(function () {
Route::redirect('/HomePage', '/home');
Route::get('/StudentPage', function () {
	return redirect()->route('students');
});
Route::redirect('/AboutPage', '/about');    
});
