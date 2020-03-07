<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class ListModels implements StrategyInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function execute($data)
    {
        return (new Model(DB::getMySqlInstance(), $data['model']))->get();
    }
}