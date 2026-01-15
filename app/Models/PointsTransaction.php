<?php
namespace App\Models;
use PDO;
use App\Core\Config\Database;

class PointsTransaction
{
    private $db;
    private $table = 'points_transactions';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Business Logic: 10 points for every 100€
    public function calculatePoints($amount)
    {
        return (int)(floor($amount / 100) * 10);
    }
    
    public function recordTransaction($userId, $points, $type, $description = '')
    {
        try {
            $this->db->beginTransaction();
            
            // Get current user points
            $userModel = new User();
            $user = $userModel->findById($userId);
            
            if (!$user) {
                throw new \Exception("User not found");
            }
            
            $currentPoints = $user['total_points'] ?? 0;
            $newTotal = 0;
            
            // Map EARNED/SPENT to database enum values (earned/redeemed)
            $dbType = '';
            if ($type === 'EARNED') {
                $dbType = 'earned';
                $newTotal = $currentPoints + $points;
            } elseif ($type === 'SPENT') {
                $dbType = 'redeemed';
                $newTotal = $currentPoints - $points;
                if ($newTotal < 0) {
                    throw new \Exception("Insufficient points");
                }
                // Make points negative for redeemed transactions
                $points = -$points;
            } else {
                // Fallback
                $dbType = 'earned';
                $newTotal = $currentPoints; 
            }
            
            // 1. Record the transaction with balance_after
            $sql = "INSERT INTO {$this->table} (user_id, type, amount, description, balance_after, created_at) 
                    VALUES (:user_id, :type, :amount, :description, :balance_after, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':type' => $dbType,
                ':amount' => $points,
                ':description' => $description,
                ':balance_after' => $newTotal
            ]);
            
            // 2. Update User's total points
            $userModel->updateTotalPoints($userId, $newTotal);
            
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Log error or rethrow
            throw $e;
        }
    }
    
    public function getUserTransactions($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>