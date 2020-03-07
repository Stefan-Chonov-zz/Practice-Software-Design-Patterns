<?php

namespace App\Strategies;

use App\Core\Interfaces\StrategyInterface;

class SampleStrategy implements StrategyInterface
{
    /**
     * @param array $data
     */
    public function execute($data)
    {
        // TODO: Implement execute() method.
        // return (new Model(DB::getMySqlInstance(), $data['model']))->func($data['data']);
    }
}