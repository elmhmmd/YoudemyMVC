<?php

namespace App\Models;

use PDO;
use App\Classes\User as UserClass;
use App\Models\isActive;
use App\Lib\Database;

class Student implements isActive
{
    private $db;
    public function  __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getStudents()
    {
        $sql = "SELECT u.* , u.id AS user_id FROM users u JOIN role ON u.role_id = role.id WHERE role.name = 'student'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $students = [];
        foreach ($result as $student) {
            $students[] = new UserClass($student['user_id'], $student['username'], $student['email'], "", $student['role_id'], $student['isactive']);
        }
        return $students;
    }
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET isActive = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":status", (int)$status, PDO::PARAM_INT);
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public static function isActive($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT isActive FROM users WHERE id = :id");
        $stmt->bindValue(":id",$userId);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }

    public static function best3Students(){
        $db = Database::getConnection();
        $stmt = $db->prepare(" SELECT u.*, COUNT(e.course_id) AS enrolled_courses
        FROM users u
        JOIN enrollement e ON e.user_id = u.id
        GROUP BY u.id
        ORDER BY enrolled_courses DESC
        LIMIT 3
         ");
         $stmt->execute();
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $result ;
    }
}
