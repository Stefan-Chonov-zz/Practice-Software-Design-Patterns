<?php

namespace App\Core\Response;

use App\Core\Interfaces\StrategyInterface;
use App\Core\Response;

class JsonResponse implements StrategyInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function execute($data = [])
    {
        return Response::toJson($data);
    }
}