<?php

use App\Core\BaseRoute;
use App\Core\Strategy;

class Custom extends BaseRoute
{
    protected $modelName;

    /**
     * Custom constructor.
     */
    public function __construct()
    {
        $this->modelName = get_class($this);

         parent::__construct($this->modelName);
    }

    /**
     * Sample code
     * @param array $data
     * @return void
     */
    public function index($data = [])
    {
        // Sample code
        // $strategy = $this->defineStrategy($_SERVER['REQUEST_METHOD'], $data);
        // $response = $strategy->execute($this->modelName, $data);

        // header('Content-Type: application/json');
        // echo json_encode($response);

        // parent::index($data);
    }

    /**
     * Sample code
     * @param $requestMethod
     * @param array $data
     * @return Strategy
     */
    protected function defineStrategy($requestMethod, $data = [])
    {
        // Sample code
        /*
        $strategy = new Strategy(new ClassNameA());
        switch ($requestMethod) {
            case 'GET':
                if (isset($data) && count($data) > 0) {
                    $strategy->setStrategy(new ClassNameB());
                }
                break;
            case 'POST':
                $strategy->setStrategy(new ClassNameC());
                break;
            case 'PUT':
                $strategy->setStrategy(new ClassNameD());
                break;
            case 'DELETE':
                $strategy->setStrategy(new ClassNameE());
                break;
        }

        return $strategy;
        */
    }
}