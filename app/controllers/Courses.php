<?php

use App\Lib\Controller;
use App\Models\Course;
use App\Classes\Course as CourseClass;
use App\Models\Student;
use App\Classes\Enrollment as EnrollmentClass;
use App\Classes\Tag;
use App\Models\Enrollment;
use App\Models\Teacher;

class Courses extends Controller
{
    public function index()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->view("courses");
        } else {
            header('Content-Type: application/json');
            try {
                $data = json_decode(file_get_contents("php://input"), true);

                $page = isset($data['page']) ? (int)$data['page'] : 1;
                $limit = isset($data['limit']) ? (int)$data['limit'] : 9;
                $search = isset($data['search']) ? trim($data['search']) : null;


                $result = Course::getAllPublishedCourses($page, $limit, $search);

                echo json_encode([
                    'status' => 'success',
                    'courses' => $result['courses'],
                    'totalPages' => $result['totalPages'],
                    'currentPage' => $result['currentPage'],
                    'totalRecords' => $result['totalRecords']
                ]);
            } catch (Exception $e) {
                // Return error response
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'An error occurred: ' . $e->getMessage()
                ]);
            }
        }
    }
    public function details($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $course = null;
            $userId = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null;
            if ($id) {
                try {
                    $course = Course::getCourseDetails($id, $userId);
                    if (!$course) {
                        echo "<div class='container text-center my-20 mx-auto p-6 text-red-500'>Course not found.</div>";
                        exit;
                    }
                } catch (\Exception $e) {
                    echo "<div class='container text-center my-20 mx-auto p-6 text-red-500'>Error: Unable to load course details. Please try again later.</div>";
                    echo $e->getMessage();
                    exit;
                }
            }
            $this->view("courseDetails", $course);
        }
    }
    public function enrolle($courseId)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userId =   $_SESSION["user"]["id"];
            $courseId = $_POST["courseId"] ?? null;
            if (!$userId) {
                redirect("");
                exit();
            }

            if (!is_numeric($courseId)) {
                redirect("");
                exit();
            }

            $userActive = Student::isActive($userId);
            if (!$userActive) {
                redirect("activeAccount");
                exit();
            }

            try {
                $newEnrollement = new EnrollmentClass($userId, $courseId, "");
                $insertdEnrollement = new Enrollment();

                if ($insertdEnrollement->createEnrollment($newEnrollement)) {
                    header("location: /uknow/pages/courseDetails.php?id=$courseId");
                    redirect("courses/details/{$courseId}");
                    exit();
                } else {
                    redirect("");
                    exit();
                }
            } catch (\Throwable $th) {
                error_log($th->getMessage());
                redirect("");
                exit();
            }
        }
    }
    public function teacher()
    {
        try {
            $userId = $_SESSION['user']['id'];
            $courses = Course::getCoursesByUserId($userId);

            $formattedCourses = [];
            foreach ($courses as $course) {
                $formattedCourses[] = $course->toArray();
            }
            echo json_encode(["courses" => $formattedCourses]);
        } catch (\Throwable $th) {
            echo json_encode(["error" => $th->getMessage()]);
        }
    }
    public function update($courseId)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $isEdit = true;
            $course = Course::getCourseById($courseId);
            $pageTitle = $isEdit ? "Edit Course: " . htmlspecialchars($course[$courseId]->getTitle()) : "Add New Course";
            $this->view("teacher/createCourse", [
                "isEdit" => $isEdit,
                "course" => $course,
                "courseId" => $courseId,
                "pageTitle" => $pageTitle,
            ]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $courseId = $_POST["courseId"];
            $title = $_POST["title"];
            $description = $_POST["description"];
            $document = $_POST["document"] ?? '';
            $categoryId = $_POST["category"] ?? '';
            $tags = json_decode($_POST["tags"]);
            $thumbFileName = null;
            // check the inputs

            if (!$title || !$description || !$categoryId || !$tags) {
                echo json_encode(["error" => "All fields are required"]);
            }
            // upload the thumbainl image
            if (isset($_FILES["thumbnail"])) {
                try {
                    $thumbFileName = uploadThumb($_FILES);
                } catch (Error $e) {
                    echo json_encode(["error" => "Thumbnail upload failed: " . $e->getMessage()]);
                    exit();
                }
            }
            $videoFileName = null;
            // upload the video if exists
            if (isset($_FILES["video"])) {
                $videoFileName = uploadVideo($_FILES);
            }


            // check if the user is a teacher
            $newTeacher = new Teacher();
            $teacher = $newTeacher->isATeacher();

            if (!$teacher["exist"]) {
                echo json_encode(["error" => "You are not allowed to this action."]);
                exit();
            }

            // create an array of tag object
            $TagCont = [];
            foreach ($tags as $tag) {
                $TagCont[] = new Tag($tag, "");
            }
            try {
                $newCourse = new CourseClass($courseId, $title, $description, $thumbFileName ? $thumbFileName : "", $videoFileName ? $videoFileName : "", $document, $categoryId, "", $teacher["id"], $TagCont, "", "", "");
                if (Course::updateCourse($newCourse)) {
                    echo json_encode(["success" => "Course updated successfully"]);
                    exit();
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $isEdit = false;
            $pageTitle =  "Add New Course";
            $this->view("teacher/createCourse", [
                "isEdit" => $isEdit,
                "course" => null,
                "courseId" => null,
                "pageTitle" => $pageTitle,
            ]);
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $document = $_POST["document"] ?? '';
            $categoryId = $_POST["category"] ?? '';
            $tags = json_decode($_POST["tags"]);

            // check the inputs

            if (!$title || !$description || !$categoryId) {
                echo json_encode(["error" => "All fields are required"]);
            }
            // upload the thumbainl image
            try {
                $thumbFileName = uploadThumb($_FILES);
            } catch (Error $e) {
                echo json_encode(["error" => "Thumbnail upload failed: " . $e->getMessage()]);
                exit();
            }
            $videoFileName = null;
            // upload the video if exists
            if (isset($_FILES["video"])) {
                $videoFileName = uploadVideo($_FILES);
            }


            // check if the user is a teacher
            $newTeacher = new Teacher();
            $teacher = $newTeacher->isATeacher();

            if (!$teacher["exist"]) {
                echo json_encode(["error" => "You are not allowed to this action."]);
                exit();
            }

            // create an array of tag object
            $TagCont = [];
            foreach ($tags as $tag) {
                $TagCont[] = new Tag($tag, "");
            }
            // create the ourse
            $newCourse = new CourseClass(0, $title, $description, $thumbFileName, $videoFileName ? $videoFileName : "", $document, $categoryId, "", $teacher["id"], $TagCont, "", "", "");
            $courseAction = new Course();
            if ($courseAction->createCourse($newCourse)) {
                echo json_encode(["success" => "Course created successfully"]);
                exit();
            } else {
                echo json_encode(["error" => "Somthing went wrong"]);
                exit();
            }
        }
    }
}
