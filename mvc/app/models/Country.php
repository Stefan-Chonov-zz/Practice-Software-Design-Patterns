<?php

namespace Models;

use Core\Model;

class Country extends Model
{
    protected $db;
    protected $table;

    /**
     * Country constructor.
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->table = 'country';
        parent::__construct($this->db, $this->table);
    }
}