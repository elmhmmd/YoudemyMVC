<?php
    use App\Lib\Controller;
    use App\Models\Course;
    class Pages extends Controller {
        public function index(){
            $courses = Course::get4PublishedCoures();
            $this->view("index",$courses);
        }
    }