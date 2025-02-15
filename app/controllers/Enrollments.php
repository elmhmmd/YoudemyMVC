<?php
    use App\Lib\Controller;
    use App\Models\Enrollment;
    class Enrollments extends Controller {
        public function index(){
            $this->valideRoleUser("student");
            $user_id = $_SESSION["user"]["id"];
            $courses = Enrollment::getAllCourseEnrolledByUserId($user_id);
            $this->view("MyCourses",$courses);
        }
    }