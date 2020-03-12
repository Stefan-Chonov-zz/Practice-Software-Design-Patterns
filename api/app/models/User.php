<?php

namespace App\Models;

use App\Core\DB;
use App\Core\Helpers\ClassName;
use App\Core\Model;

class User extends Model
{
    public function __construct()
    {
        parent::__construct(ClassName::getShortName($this), DB::getMySqlInstance());
    }
}