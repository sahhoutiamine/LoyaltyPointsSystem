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
}
?>