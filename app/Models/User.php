<?php


class User
{
    private $db;
    private $table = 'users';
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
 
    
}
?>