<?php
namespace App\Models;
use App\Core\Config\Database;

class Reward
{
    private $db;
    private $table = 'rewards';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, description, points_required, stock) 
                VALUES (:name, :description, :points_required, :stock)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':points_required' => $data['points_required'],
            ':stock' => $data['stock'] ?? -1  // Default to unlimited stock (-1)
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY points_required ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET name = :name, 
                    description = :description, 
                    points_required = :points_required,
                    stock = :stock
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':points_required' => $data['points_required'],
            ':stock' => $data['stock'] ?? -1
        ]);
    }
}
?>