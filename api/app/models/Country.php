<?php

namespace App\Models;

use App\Core\Helpers\ClassName;
use App\Core\Model;
use App\Core\DB;

class Country extends Model
{
    public function __construct()
    {
        parent::__construct(ClassName::getShortName($this), DB::getMySqlInstance());
    }
}