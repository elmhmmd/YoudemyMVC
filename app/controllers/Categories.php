<?php

use App\Lib\Controller;
use App\Classes\Category as CategoryClass;

class Categories extends Controller
{
    public function index()
    {
        $categories = $this->modal("Category");
        $categories = $categories->getCategories();
        $serializedCategories = [];
        foreach ($categories as $category) {
            $serializedCategories[] = ["id"=> $category->getId(), "name" => $category->getName()];
        }
        echo json_encode(["categories" => $serializedCategories]);
    }
    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
        
            $Categories = $data['categories'] ?? null;
            if($Categories === null || empty($Categories)){
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }
        
            $failedCategories = 0;
            $successCategories = 0;
            try {
                foreach($Categories as $category){
                    $catObj = new CategoryClass(0,$category);
                    $newCategory = $this->modal("Category");
                    if($newCategory->checkExistence($catObj)){
                        $failedCategories++;
                    }else {
                        if($newCategory->createCategory($catObj)){
                            $successCategories++;
                        }
                    }
                }
                echo json_encode(["message" => "Categories created successfully.", "failed" => $failedCategories, "success" => $successCategories]);
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
        
            $Categories = $data['categories'] ?? null;
            $categoryId = $data['id'] ?? null;
        
            if($Categories === null || empty($Categories)|| $categoryId === null){
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }
        
            if(count($Categories) > 1){
                echo json_encode(["error" => "Only one category can be updated at a time."]);
                exit();
            }
        
            try {
                $catObject = new CategoryClass($categoryId,$Categories[0]);
                $updateCategory = $this->modal("Category");
                if($updateCategory->updateCategory($catObject)){
                    echo json_encode(["message" => "Category updated successfully."]);
                    exit();
                }else {
                    echo json_encode(["error" => "Category not found."]);
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
        
            $categoryId = $data['id'] ?? null;
        
            if($categoryId === null){
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }
            try {
                $catObject = new CategoryClass($categoryId, "");
                $category = $this->modal("Category");
                if($category->deleteCategory($catObject)){
                    echo json_encode(["message" => "Category deleted successfully."]);
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
}
