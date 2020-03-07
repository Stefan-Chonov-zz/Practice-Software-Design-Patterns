<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class UpdateModel implements StrategyInterface
{
    /**
     * @param $model
     * @param $data
     * @return int
     */
    public function execute($model, $data)
    {
        return (new Model(DB::getMySqlInstance(), $model))->update($data);
    }
}