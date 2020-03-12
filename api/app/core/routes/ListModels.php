<?php

namespace App\Core\Routes;

use App\Core\Interfaces\StrategyInterface;
use App\Core\DB;
use App\Core\Model;

class ListModels implements StrategyInterface
{
    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function execute($data)
    {
        return (new Model($data['model'], DB::getMySqlInstance()))->get();
    }
}