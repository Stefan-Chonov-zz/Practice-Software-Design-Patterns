<?php

use App\Core\Helpers\RequestMethod;
use App\Core\Helpers\StringHelper;
use App\Core\Log;
use App\Models\Country as CountryModel;
use App\Routes\Model;

class Country extends Model
{
    private $log;
    private $countryModel;

    protected $modelName;

    /**
     * Country constructor.
     */
    public function __construct()
    {
        $this->log = Log::getInstance(env('LOG_PATH'));
        $this->countryModel = new CountryModel();

        parent::__construct();
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function index($data = [])
    {
        $listRequiredFieldsNames = [];
        if ($_SERVER['REQUEST_METHOD'] == RequestMethod::POST) {
            $listRequiredFieldsNames = [ 'name', 'key' ];
        } else if ($_SERVER['REQUEST_METHOD'] == RequestMethod::PUT || $_SERVER['REQUEST_METHOD'] == RequestMethod::DELETE) {
            $listRequiredFieldsNames = [ 'id' ];
        }

        $listValidParametersNames = [ 'id', 'name', 'key', 'responseFormat' ];

        parent::baseIndex($data, $listRequiredFieldsNames, $listValidParametersNames);
    }

    /**
     * @param $inputs
     * @return array
     */
    protected function inputsValidation($inputs)
    {
        try {
            $errors = [];

            foreach ($inputs as $key => $value) {
                switch ($key) {
                    case 'id':
                        break;
                    case 'name':
                    case 'key':
                        $isKeyEmpty = StringHelper::isEmpty($inputs[$key]);
                        if (isset($inputs[$key]) && $isKeyEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $isKeyContainsOnlyLetters = StringHelper::hasOnlyLetters($inputs[$key]);
                            if (!$isKeyContainsOnlyLetters) {
                                $errors[$key] = ['message' => "Not valid $key"];
                            }
                        }

                        $country = $this->countryModel->get([ $key => $inputs[$key] ]);
                        if (isset($country) && !empty($country)) {
                            if (!isset($inputs['id']) || (isset($inputs['id']) && ($inputs['id'] != array_shift($country)['id']))) {
                                $errors[$key] = [ 'message' => 'Record already exists' ];
                            }
                        }
                        break;
                }
            }

            return $errors;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}