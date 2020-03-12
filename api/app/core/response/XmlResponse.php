<?php

namespace App\Core\Response;

use App\Core\Interfaces\StrategyInterface;
use App\Core\Response;

class XmlResponse implements StrategyInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function execute($data = [])
    {
        if (!empty($data)) {
            return Response::toXml($data);
        }
        
        return '';
    }
}