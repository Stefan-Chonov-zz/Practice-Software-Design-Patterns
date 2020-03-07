<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class SearchModels implements StrategyInterface
{
    public function execute($model, $data)
    {
        return (new Model(DB::getMySqlInstance(), $model))->get($data);
    }
}