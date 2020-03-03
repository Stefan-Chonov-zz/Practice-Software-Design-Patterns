<?php

namespace Models;

use Core\DB;
use Core\Model;

class Country extends Model
{
    protected $db;
    protected $table;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
        $this->table = 'country';
        parent::__construct($this->db, $this->table);
    }
}