<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class CreateModel implements StrategyInterface
{
    /**
     * @param string $model
     * @param array $data
     * @return int
     */
    public function execute($model, $data)
    {
        return (new Model(DB::getMySqlInstance(), $model))->create($data);
    }
}