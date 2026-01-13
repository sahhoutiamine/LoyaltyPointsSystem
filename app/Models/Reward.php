<?php

namespace App\Models;

class User
{
    private $db;
    private $table = 'rewards';
    
    public function __construct($db)
    {
        $this->db = $db;
    }
}
?>