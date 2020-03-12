<?php

namespace App\Routes;

use App\Core\BaseRoute;
use App\Core\Helpers\ClassName;
use App\Core\Helpers\RequestMethod;
use App\Core\Log;

abstract class Model extends BaseRoute
{
    private $log;

    /**
     * Country constructor.
     */
    public function __construct()
    {
        $this->log = new Log(env('LOG_PATH'));

        parent::__construct(ClassName::getShortName($this));
    }

    /**
     * @param array $requiredFields
     * @param array $data
     * @return void
     */
    public function baseIndex($data = [], $requiredFields = [])
    {
        try {
            $responseFormat = '';
            if (isset($data['responseFormat']) && !empty($data['responseFormat'])) {
                $responseFormat = $data['responseFormat'];
                unset($data['responseFormat']);
            }

            $responseStatus = [];
            $responseMessage = '';
            $responseCode = '';
            switch ($_SERVER['REQUEST_METHOD']) {
                case RequestMethod::GET:
                    $invalidParameters = $this->supportedFields($requiredFields, $data);
                    if (count($invalidParameters) > 0) {
                        $response = $invalidParameters;
                        $responseMessage = "Occur error";
                        $responseCode = 'Error';
                    } else {
                        $response = parent::request($_SERVER['REQUEST_METHOD'], $data);
                    }
                    break;
                case RequestMethod::POST:
                case RequestMethod::PUT:
                    $missingFields = $this->requiredFields($requiredFields, $data);
                    $inputValidationResults = array_merge($missingFields, $this->inputValidation($data));
                    if (isset($inputValidationResults) && empty($inputValidationResults)) {
                        $response = parent::request($_SERVER['REQUEST_METHOD'], $data);
                        if ($response > 0) {
                            $responseMessage = "Record is stored successfully";
                            $responseCode = 'OK';
                        }
                    } else {
                        $response = $inputValidationResults;
                        $responseMessage = "Occur error";
                        $responseCode = 'Error';
                    }
                    break;
                case RequestMethod::DELETE:
                    $response = parent::request($_SERVER['REQUEST_METHOD'], $data);
                    if ($response > 0) {
                        $responseMessage = "Delete is successful";
                        $responseCode = 'OK';
                    } else {
                        $responseMessage = "Record not exists";
                        $responseCode = 'Error';
                    }
                    break;
            }

            if (!empty($responseMessage) && !empty($responseCode)) {
                $response = is_array($response) ? $response : [];
                $responseStatus['message'] = $responseMessage;
                $responseStatus['status'] = $responseCode;

                $response = array_merge($responseStatus, $response);
            }

            echo parent::response($response, $responseFormat);
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $inputs
     * @return array
     */
    protected abstract function inputValidation($inputs);

    /**
     * @param $requiredFields
     * @param $array
     * @return array
     */
    private function requiredFields($requiredFields, $array)
    {
        $missingParameters = [];
        foreach ($requiredFields as $key) {
            $isKeyExists = array_key_exists($key, $array);
            if (!$isKeyExists) {
                $missingParameters[$key] = [ 'message' => "Parameter '$key' is required" ];
            }
        }

        return $missingParameters;
    }

    /**
     * @param $validFields
     * @param $array
     * @return array
     */
    private function supportedFields($validFields, $array)
    {
        $invalidParameters = [];
        foreach ($array as $key => $value) {
            $isKeyExists = in_array($key, $validFields);
            if (!$isKeyExists) {
                $invalidParameters[$key] = [ 'message' => "Invalid parameter '$key'" ];
            }
        }

        return $invalidParameters;
    }
}