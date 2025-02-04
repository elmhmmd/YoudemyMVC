<?php

use App\Lib\Controller;

use App\Models\Course;
use App\Models\Student;

class Admin extends Controller
{
    public function __construct() {}

    public function dashboard()
    {
        $coursesCount = Course::countCourses();
        $courseDistribution = Course::courseDistribution();
        $best3Courses = Course::getBest3Courses();
        $best3Students = Student::best3Students();
        $this->view("admin/dashboard", [
            "coursesCount" => $coursesCount,
            "courseDistribution" => $courseDistribution,
            "best3Courses" => $best3Courses,
            "best3Students" => $best3Students
        ]);
    }
    public function students()
    {
        $this->view("admin/students");
    }
    public function teachers()
    {
        $this->view("admin/teachers");
    }
    public function tags()
    {
        echo json_encode(["tags" => ["tag1","tag2"]]);
        $this->view("admin/tags");
    }
    public function categories()
    {
        $this->view("admin/categories");
    }
    public function allStudents()
    {
        try {
            $student = $this->modal("Student");
            $students = $student->getStudents();
            $serializedtudents = [];

            foreach ($students as $student) {
                $serializedtudents[] = [
                    "id" => $student->getId(),
                    "username" => $student->getUsername(),
                    "email" => $student->getEmail(),
                    "isActive" => $student->getIsActive()
                ];
            }
            echo json_encode(["students" => $serializedtudents]);
        } catch (\Throwable $th) {
            echo json_encode(["error" => $th->getMessage()]);
        }
    }
    public function allTeachers()
    {
        try {
            $teacher = $this->modal("Teacher");
            $teachers = $teacher->getTeachers();
            $serializedstudents = [];

            foreach ($teachers as $teacher) {
                $serializedstudents[] = [
                    "id" => $teacher->getId(),
                    "username" => $teacher->getUsername(),
                    "email" => $teacher->getEmail(),
                    "isActive" => $teacher->getIsActive()
                ];
            }
            echo json_encode(["teachers" => $serializedstudents]);
        } catch (\Throwable $th) {
            echo json_encode(["error" => $th->getMessage()]);
        }
    }
    public function toggleStatus()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);

            $studentId = $data['id'] ?? null;
            $status = $data['status'] ?? false;

            if ($studentId === null) {
                echo json_encode(["error" => "Id is required."]);
                exit();
            }


            try {
                $studentObject = new Student();
                if ($studentObject->updateStatus($studentId, $status ? 1 : 0)) {
                    echo json_encode(["message" => "Status updated successfully."]);
                    exit();
                } else {
                    echo json_encode(["error" => "student not found."]);
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
}
