<?php

namespace App\Core;

use App\Core\Strategy;
use App\Core\Routes\CreateModel;
use App\Core\Routes\DeleteModel;
use App\Core\Routes\ListModels;
use App\Core\Routes\SearchModels;
use App\Core\Routes\UpdateModel;
use App\Core\Response\CsvResponse;
use App\Core\Response\JsonResponse;
use App\Core\Response\XmlResponse;
use App\Core\Helpers\RequestMethod;
use App\Core\Helpers\ResponseFormat;

abstract class BaseRoute
{
    private $log;
    protected $modelName;

    /**
     * BaseRoute constructor.
     * @param string $modelName
     */
    protected function __construct($modelName)
    {
        $this->log = new Log(env('LOG_PATH'));
        $this->modelName = $modelName;
    }

    /**
     * @param array $data
     * @return void
     */
    protected function baseIndex($data = [])
    {
        try {
            $responseFormat = '';
            if (isset($data['responseFormat']) && !empty($data['responseFormat'])) {
                $responseFormat = $data['responseFormat'];
                unset($data['responseFormat']);
            }

            $response = $this->request($_SERVER['REQUEST_METHOD'], $data);
            echo $this->response($response, $responseFormat);
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param string $requestMethod
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    protected function request($requestMethod, $data = [])
    {
        try {
            $requestMethod = strtoupper(trim($requestMethod));
            $strategy = new Strategy(new ListModels());
            switch ($requestMethod) {
                case RequestMethod::GET:
                    if (isset($data) && count($data) > 0) {
                        $strategy->setStrategy(new SearchModels());
                    }
                    break;
                case RequestMethod::POST:
                    $strategy->setStrategy(new CreateModel());
                    break;
                case RequestMethod::PUT:
                    $strategy->setStrategy(new UpdateModel());
                    break;
                case RequestMethod::DELETE:
                    $strategy->setStrategy(new DeleteModel());
                    break;
            }

            return $strategy->execute([ 'model' => $this->modelName, 'data' => $data ]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $response
     * @param string $responseFormat
     * @return mixed
     * @throws \Exception
     */
    protected function response($response = [], $responseFormat = ResponseFormat::JSON)
    {
        try {
            $responseFormat = strtoupper(trim($responseFormat));
            $strategy = new Strategy(new JsonResponse());
            $contentType = 'application/json';
            switch ($responseFormat) {
                case ResponseFormat::XML:
                    $contentType = 'application/xml';
                    $strategy->setStrategy(new XmlResponse());
                    break;
                case ResponseFormat::CSV:
                    $contentType = 'text/csv';
                    $strategy->setStrategy(new CsvResponse());
                    break;
            }

            header("Content-Type: $contentType");
            return $strategy->execute($response);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}