<?php

namespace App\Core\Strategies;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class UpdateModel implements StrategyInterface
{
    /**
     * @param array $data
     * @return int
     */
    public function execute($data)
    {
        return (new Model(DB::getMySqlInstance(), $data['model']))->update($data['data']);
    }
}