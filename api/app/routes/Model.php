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
        $this->log = Log::getInstance(env('LOG_PATH'));

        parent::__construct(ClassName::getShortName($this));
    }

    /**
     * @param array $inputs
     * @param array $listRequiredParametersNames
     * @param array $listValidParametersNames
     * @return void
     */
    protected function baseIndex($inputs = [], $listRequiredParametersNames = [], $listValidParametersNames = [])
    {
        try {
            $responseFormat = '';
            if (isset($inputs['responseFormat']) && !empty($inputs['responseFormat'])) {
                $responseFormat = $inputs['responseFormat'];
                unset($inputs['responseFormat']);
            }

            $response = [];
            $responseStatus = [];

            $inputsValidationResults = $this->userInputValidation($inputs, $listRequiredParametersNames, $listValidParametersNames);
            if (!isset($inputsValidationResults) || empty($inputsValidationResults)) {
                $response = parent::request($_SERVER['REQUEST_METHOD'], $inputs);
                switch ($_SERVER['REQUEST_METHOD']) {
                    case RequestMethod::GET:
                        if (empty($response)) {
                            $responseStatus = $this->defineResponseStatus("No results found", "OK");
                        }
                        break;
                    case RequestMethod::POST:
                    case RequestMethod::PUT:
                        if ($response > 0) {
                            $responseStatus = $this->defineResponseStatus("Record is stored successfully", "OK");
                        } else {
                            $responseStatus = $this->defineResponseStatus("Nothing to insert/update", "OK");
                        }
                        $response = [];
                        break;
                    case RequestMethod::DELETE:
                        if ($response > 0) {
                            $responseStatus = $this->defineResponseStatus("Delete is successful", "OK");
                        } else {
                            $responseStatus = $this->defineResponseStatus("Record not exists", "OK");
                        }
                        $response = [];
                        break;
                }
            } else {
                $responseStatus = $this->defineResponseStatus("Occur error", "ERROR");
            }

            if (!empty($responseStatus)) {
                $response = array_merge($responseStatus, $inputsValidationResults, $response);
            }

            echo parent::response($response, $responseFormat);
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }

    /**
     * @param $inputs
     * @param $requiredFields
     * @param $validFields
     * @return array
     */
    private function userInputValidation($inputs, $requiredFields, $validFields)
    {
        $putRequiredFields = [ 'id' ];
        $deleteRequiredFields = [ 'id' ];

        $inputValidationResults = [];
        $missingRequiredFields = [];

        switch ($_SERVER['REQUEST_METHOD']) {
            case RequestMethod::PUT:
                $requiredFields = $putRequiredFields;
            case RequestMethod::POST:
                $missingRequiredFields = $this->checkForMissingRequiredFields($requiredFields, $inputs);
                $inputValidationResults = $this->inputsValidation($inputs);
                break;
            case RequestMethod::DELETE:
                $missingRequiredFields = $this->checkForMissingRequiredFields($deleteRequiredFields, $inputs);
                break;
        }

        $invalidFields = $this->checkForInvalidFields($validFields, $inputs);

        return array_merge($invalidFields, $inputValidationResults, $missingRequiredFields);
    }

    private function defineResponseStatus($message, $status)
    {
        return [ 'message' => $message, 'status' => $status ];
    }

    /**
     * @param $inputs
     * @return array
     */
    protected abstract function inputsValidation($inputs);

    /**
     * @param $requiredFields
     * @param $inputs
     * @return array
     */
    protected function checkForMissingRequiredFields($requiredFields, $inputs)
    {
        $missingParameters = [];
        foreach ($requiredFields as $key) {
            $isKeyExists = array_key_exists($key, $inputs);
            if (!$isKeyExists) {
                $missingParameters[$key] = [ 'message' => "Parameter '$key' is required" ];
            }
        }

        return $missingParameters;
    }

    /**
     * @param $validFields
     * @param $inputs
     * @return array
     */
    protected function checkForInvalidFields($validFields, $inputs)
    {
        $invalidParameters = [];
        foreach ($inputs as $key => $value) {
            $isKeyExists = in_array($key, $validFields);
            if (!$isKeyExists) {
                $invalidParameters[$key] = [ 'message' => "Invalid parameter '$key'" ];
            }
        }

        return $invalidParameters;
    }
}