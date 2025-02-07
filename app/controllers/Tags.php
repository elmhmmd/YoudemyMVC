<?php

use App\Lib\Controller;
use App\Classes\Tag as TagClass;

class Tags extends Controller
{
    public function index()
    {
        $tagObj = $this->modal("Tag");
        $tags = $tagObj->getTags();
        $serializedTags = [];
        foreach ($tags as $tag) {
            $serializedTags[] = ["id" => $tag->getId(), "name" => $tag->getName()];
        }
        echo json_encode(["tags" => $serializedTags]);
    }
    public function create()
    {
        $this->valideRoleUser("admin");
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);

            $tags = $data['tags'] ?? null;
            $csrf_token = $data['csrf_token'] ?? null;

            if(!$this->validateCsrfToken($csrf_token)){
                return ;
            }
            if ($tags === null || empty($tags)) {
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }

            $failedTags = 0;
            $successTags = 0;
            try {
                foreach ($tags as $tag) {
                    $TagObj = new TagClass(0, $tag);
                    $newTag = $this->modal("Tag");
                    if ($newTag->checkExistence($TagObj)) {
                        $failedTags++;
                    } else {
                        if ($newTag->createTag($TagObj)) {
                            $successTags++;
                        }
                    }
                }
                echo json_encode(["message" => "Tags created successfully.", "failed" => $failedTags, "success" => $successTags]);
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
    public function update()
    {
        $this->valideRoleUser("admin");
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);

            $tags = $data['tags'] ?? null;
            $tagId = $data['id'] ?? null;
            $csrf_token = $data['csrf_token'] ?? null;

            if(!$this->validateCsrfToken($csrf_token)){
                return ;
            }

            if ($tags === null || empty($tags) || $tagId === null) {
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }

            if (count($tags) > 1) {
                echo json_encode(["error" => "Only one tag can be updated at a time."]);
                exit();
            }

            try {
                $tagObject = new TagClass($tagId, $tags[0]);
                $updateCategory = $this->modal("Tag");
                if ($updateCategory->updateTag($tagObject)) {
                    echo json_encode(["message" => "Tag updated successfully."]);
                    exit();
                } else {
                    echo json_encode(["error" => "Tag not found."]);
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
    public function delete()
    {
        $this->valideRoleUser("admin");
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents('php://input'), true);

            $tagId = $data['id'] ?? null;
            $csrf_token = $data['csrf_token'] ?? null;

            if(!$this->validateCsrfToken($csrf_token)){
                return ;
            }

            if ($tagId === null) {
                echo json_encode(["error" => "All fields are required."]);
                exit();
            }
            try {
                $tagObject = new TagClass($tagId, "");
                $tag = $this->modal("Tag");
                if ($tag->deleteTag($tagObject)) {
                    echo json_encode(["message" => "Category deleted successfully."]);
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        }
    }
}
