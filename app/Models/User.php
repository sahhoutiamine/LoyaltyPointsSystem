<?php


class User
{
    private $db;
    private $table = 'users';
    
    public function __construct($db)
    {
        $this->db = $db;
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
    
}
?>