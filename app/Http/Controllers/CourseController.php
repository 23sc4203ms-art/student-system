<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('students')->orderBy('id')->get();

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => ['required', 'string', 'max:255', 'unique:courses,course_name'],
        ]);

        $course = Course::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'action' => 'created', 'model' => 'course', 'record' => ['id' => $course->id, 'course_name' => $course->course_name]]);
        }

        return redirect()->route('courses.show', $course->id)->with('success', 'Course added successfully.');
    }

    public function show(string $id)
    {
        $course = Course::with('students')->findOrFail($id);

        return view('courses.show', compact('course'));
    }

    public function enrollmentForm()
    {
        $courses = Course::orderBy('course_name')->get();
        $students = Student::orderBy('Lname')->orderBy('Fname')->get();

        return view('courses.enroll', compact('courses', 'students'));
    }

    public function enrollStudent(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $student->courses()->syncWithoutDetaching([$validated['course_id']]);

        return redirect()->route('courses.index')->with('success', 'Student enrolled successfully.');
    }

    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'course', 'record' => ['id' => $id]]);
        }

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
