<?php

namespace App\Core;

use App\Core\Strategy;
use App\Core\Strategies\CreateModel;
use App\Core\Strategies\DeleteModel;
use App\Core\Strategies\ListModels;
use App\Core\Strategies\SearchModels;
use App\Core\Strategies\UpdateModel;

abstract class BaseRoute
{
    protected $modelName;

    /**
     * BaseRoute constructor.
     * @param $modelName
     */
    protected function __construct($modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * @param array $data
     * @return void
     */
    protected function index($data = [])
    {
        $strategy = $this->defineStrategy($_SERVER['REQUEST_METHOD'], $data);
        $response = $strategy->execute($this->modelName, $data);

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * @param $requestMethod
     * @param array $data
     * @return Strategy
     */
    protected function defineStrategy($requestMethod, $data = [])
    {
        $strategy = new Strategy(new ListModels());
        switch ($requestMethod) {
            case 'GET':
                if (isset($data) && count($data) > 0) {
                    $strategy->setStrategy(new SearchModels());
                }
                break;
            case 'POST':
                $strategy->setStrategy(new CreateModel());
                break;
            case 'PUT':
                $strategy->setStrategy(new UpdateModel());
                break;
            case 'DELETE':
                $strategy->setStrategy(new DeleteModel());
                break;
        }

        return $strategy;
    }
}