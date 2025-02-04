<?php

use App\Lib\Controller;
use App\Models\Course;
use App\Models\Teacher as TeacherModal;

class Teacher extends Controller
{
    public function __construct() {}

    public function dashboard()
    {
        $user_id = $_SESSION["user"]["id"];
        $studentCount = TeacherModal::getStudentsCount($user_id);
        $courseCount = TeacherModal::courseCount($user_id);
        $recentCourses = TeacherModal::get3RecentCourses($user_id);
        $this->view("teacher/dashboard",[
            "studentCount" => $studentCount,
            "courseCount" => $courseCount,
            "recentCourses" => $recentCourses,
        ]);
    }
    public function courses()
    {
        $this->view("teacher/courses");
    }
    public function createCourse()
    {
        $this->view("teacher/createCourse");
    }
    public function students()
    {
        $teacher_id = $_SESSION["user"]['id'];
        $students = TeacherModal::getMyUsers($teacher_id);
        $this->view("teacher/students",$students);
    }
}
