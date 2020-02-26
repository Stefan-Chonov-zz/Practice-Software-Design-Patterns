<?php

namespace Models;

use Core\DB;
use Core\ModelRepository;

class CountryRepository extends ModelRepository
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