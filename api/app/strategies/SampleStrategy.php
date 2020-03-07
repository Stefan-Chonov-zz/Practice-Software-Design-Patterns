<?php

namespace App\Strategies;

use App\Core\Interfaces\StrategyInterface;

class SampleStrategy implements StrategyInterface
{
    /**
     * @param string $model
     * @param array $data
     */
    public function execute($model, $data)
    {
        // TODO: Implement execute() method.
        // return (new Model(DB::getMySqlInstance(), $model))->func($data);
    }
}