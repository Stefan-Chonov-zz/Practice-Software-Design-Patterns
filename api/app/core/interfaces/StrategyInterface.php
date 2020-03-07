<?php

namespace App\Core\Interfaces;

interface StrategyInterface
{
    public function execute($model, $data);
}