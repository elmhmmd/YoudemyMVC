<?php   
    namespace App\Models ;
    use App\Lib\Database;
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function register($data)
    {
        $sql = "INSERT INTO users (username, email, password_hash, role_id,isActive) 
                    VALUES (:username, :email, :password_hash, :role_id,:isActive)";

        $isActive = 1;
        if ($data["role_id"] == 2) {
            $isActive = 0;
        }

        // Hash the password
        $password_hash = password_hash($data["password"], PASSWORD_DEFAULT);

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':username', $data["username"]);
        $stmt->bindParam(':email', $data["email"]);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':role_id', $data["role_id"]);
        $stmt->bindParam(':isActive', $isActive);

        $stmt->execute();

        return $stmt->rowCount() >= 1;
    }
    public function login($data)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $data["email"]);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            if (password_verify($data["password"], $result['password_hash'])) {
                $this->setSession($result);
                return true;
            }
        }
        return false;
    }
    public function setSession($data){
        $_SESSION["user"]["id"] = $data["id"];
        $_SESSION["user"]["username"] = $data["username"];
        $_SESSION["user"]["email"] = $data["email"];
        $_SESSION["user"]["role_id"] = $data["role_id"];
    }
}
