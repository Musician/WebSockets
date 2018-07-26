<?php 

class database extends PDO
{
    public function __construct($configFile)
    {
        require_once $configFile;

        $dsn		= DB_TYPE . ':dbname='.DB_NAME.';host='.DB_HOST;
        $user		= DB_USER;
        $password	= DB_PASS;
        
        parent::__construct($dsn, $user, $password);
        
        try
        {
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
        
    }
    
    public function get($q, $args=null)
    {
        $res = array();
        try
        {
            foreach (parent::query($q) as $row) 
            {
                $res[] = $row; 
            }
            return $res;
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
    }
}