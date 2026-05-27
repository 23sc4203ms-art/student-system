<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
class PagesController extends Controller
{
    //
    public function userProfile()
    {
   // $user = auth()->user();
    $user = User::find(1); 
    echo $user->name . " - " . $user->profile->bio;

    }

    public function userPosts()
    {
        $user = User::find(1); 
        foreach ($user->posts as $post) {
            echo "$user->name: $post->title - $post->content <br> ";
        }
    }
    public function studentCourses()
    {
        $student = Student::find(5); 
        foreach ($student->courses as $course) {
            echo "$student->Fname $student->Lname is enrolled in: $course->course_name <br> ";
        }
    }

    public function maintenance (){
        return response()->view('maintenance', [], 503);
    }
}
