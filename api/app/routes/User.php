<?php

use App\Core\Helpers\ClassName;
use App\Core\Helpers\RequestMethod;
use App\Core\Log;
use App\Core\Helpers\StringHelper;
use App\Models\User as UserModel;
use App\Models\Country as CountryModel;
use App\Routes\Model;

define("PHONE_MIN_LENGTH", 7);

class User extends Model
{
    private $log;
    private $userModel;
    private $countryModel;

    protected $modelName;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->log = Log::getInstance(env('LOG_PATH'));
        $this->userModel = new UserModel();
        $this->countryModel = new CountryModel();

        parent::__construct();
    }

    /**
     * @param array $inputs
     * @return void
     * @throws Exception
     */
    public function index($inputs = [])
    {
        $listValidParametersNames = [ 'id', 'firstName', 'surName', 'address', 'countryId', 'postcode', 'phone', 'email', 'responseFormat' ];
        $listRequiredParametersNames = [];
        if ($_SERVER['REQUEST_METHOD'] == RequestMethod::POST) {
            $listRequiredParametersNames = [ 'firstName', 'surName', 'address', 'countryId', 'postcode', 'phone', 'email' ];
        } else if ($_SERVER['REQUEST_METHOD'] == RequestMethod::PUT || $_SERVER['REQUEST_METHOD'] == RequestMethod::DELETE) {
            $listRequiredParametersNames = [ 'id' ];
        }

        parent::baseIndex($inputs, $listRequiredParametersNames, $listValidParametersNames);
    }

    /**
     * @param $inputs
     * @return array
     */
    protected function inputsValidation($inputs) {
        try {
            $errors = [];

            foreach ($inputs as $key => $value) {
                switch($key) {
                    case 'id': break;
                    case 'firstName':
                    case 'surName':
                        $isSurNameEmpty = StringHelper::isEmpty($inputs[$key]);
                        if ($isSurNameEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $isSurNameContainsOnlyLetters = StringHelper::hasOnlyLetters($inputs[$key]);
                            if (!$isSurNameContainsOnlyLetters) {
                                $errors[$key] = ['message' => 'Not valid name'];
                            }
                        }
                        break;
                    case 'address':
                        $isAddressEmpty = StringHelper::isEmpty($inputs[$key]);
                        if ($isAddressEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }
                        break;
                    case 'countryId':
                        if (empty($inputs[$key])) {
                            $errors[$key] = [ 'message' => 'Select country' ];
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $country = $this->countryModel->get(['id' => $inputs[$key]]);
                            if (!$country) {
                                $errors[$key] = ['message' => 'Invalid country'];
                            }
                        }
                        break;
                    case 'postcode':
                        $isPostCodeEmpty = StringHelper::isEmpty($inputs[$key]);
                        if ($isPostCodeEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }
                        break;
                    case 'phone':
                        $isPhoneEmpty = StringHelper::isEmpty($inputs[$key]);
                        if ($isPhoneEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $isPhoneMinLengthValid = strlen($inputs[$key]) >= PHONE_MIN_LENGTH;
                            if (!$isPhoneMinLengthValid) {
                                $errors[$key] = ['message' => 'Phone should be at least 7 digits'];
                            }
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $isPhoneContainsOnlyDigits = StringHelper::hasOnlyDigits($inputs['phone']);
                            if (!$isPhoneContainsOnlyDigits) {
                                $errors[$key] = ['message' => 'Phone must contains only digits'];
                            }
                        }
                        break;
                    case 'email':
                        $isEmailEmpty = StringHelper::isEmpty($inputs[$key]);
                        if ($isEmailEmpty) {
                            $errors[$key] = [ 'message' => 'Field cannot be empty' ];
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $isEmailValid = StringHelper::isEmail($inputs[$key]);
                            if (!$isEmailValid) {
                                $errors[$key] = ['message' => 'Invalid email format'];
                            }
                        }

                        if (!isset($errors[$key]) && empty($errors[$key])) {
                            $user = $this->userModel->get([ $key => $inputs[$key] ]);
                            if ($user) {
                                $errors[$key] = [ 'message' => 'Email address is already used' ];
                            }
                        }
                        break;
                    default:
                        $errors[$key] = [ 'message' => "Invalid parameter name '$key'" ];
                        break;
                }
            }

            return $errors;
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }
    }
}