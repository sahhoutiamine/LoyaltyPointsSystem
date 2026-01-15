<?php

namespace App\Models;

use PDO;
use App\Core\Config\Database;

class User
{
    private $db;
    private $table = 'users';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (email, password_hash, name, created_at) 
                VALUES (:email, :password_hash, :name, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':name' => $data['name']
        ]);
        
        return $this->db->lastInsertId();
    }


    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTotalPoints($userId, $newTotal)
    {
        $sql = "UPDATE {$this->table} SET total_points = :total_points WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':total_points' => $newTotal,
            ':id' => $userId
        ]);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    public function emailExists($email)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
?>