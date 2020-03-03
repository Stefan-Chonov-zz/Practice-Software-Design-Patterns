<?php

namespace Models;

use Core\DB;
use Core\Log;
use Core\Model;

class User extends Model
{
    private $log;

    protected $db;
    protected $table;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->log = new Log(env('LOG_PATH'));
        $this->db = DB::getInstance();
        $this->table = 'user';
        parent::__construct($this->db, $this->table);
    }

    /**
     * Return user by email address
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        try {
            $query = "SELECT * FROM $this->table WHERE email = '$email' LIMIT 1";
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}