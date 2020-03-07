<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class ListModels implements StrategyInterface
{
    /**
     * @param string $model
     * @param array $data
     * @return array
     */
    public function execute($model, $data)
    {
        return (new Model(DB::getMySqlInstance(), $model))->get();
    }
}