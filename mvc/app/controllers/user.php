<?php

//namespace Controllers;

use Core\Controller;
use Core\DB;
use Core\Helpers\StringHelper;
use Core\Log;
use Models\User as UserModel;
use Models\Country as CountryModel;

define("PHONE_MIN_LENGTH", 7);

class User extends Controller
{
    private $log;
    private $user;
    private $country;

    public function __construct()
    {
        $this->log = new Log(env('LOG_PATH'));
        $this->user = new UserModel(DB::getInstance());
        $this->country = new CountryModel(DB::getInstance());
    }

    public function index()
    {
        header("Location: create");
    }

    public function create()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $response = $this->userFormValidation($_POST);
                if (!$response['form']['hasError']) {
                    $id = $this->user->create($_POST);
                    if ($id > 0) {
                        $response['form']['message'] = "User is stored successfully";

                        $this->log->info(json_encode($_POST));
                    }
                } else {
                    $response['form']['data'] = $_POST;
                    $response['form']['message'] = "Invalid form data";

                    $this->log->warning('Invalid input from user: ' . PHP_EOL . json_encode($response['form']['errors'], JSON_PRETTY_PRINT));
                }
            }

            $response['countries'] = $this->country->getAll();
            parent::view('user/create', $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage() . PHP_EOL . $e->getTrace());
        }
    }

    private function userFormValidation($inputs)
    {
        $errors = $this->checkUserInputForErrors($inputs);
        return [
            "form"=> [
                "message" => "",
                "errors" => $errors,
                "hasError" => count($errors) > 0
            ],
        ];
    }

    /**
     * @param $inputs
     * @return array
     */
    private function checkUserInputForErrors($inputs)
    {
        try {
            $errors = [];

            // Validate First name
            $isFirstNameEmpty = StringHelper::isEmpty($inputs['firstName']);
            if ($isFirstNameEmpty) {
                $errors['firstName'] = [ 'message' => 'Field cannot be empty' ];
            }

            if (!isset($errors['firstName']) && empty($errors['firstName'])) {
                $isFirstNameContainsOnlyLetters = StringHelper::hasOnlyLetters($inputs['firstName']);
                if (!$isFirstNameContainsOnlyLetters) {
                    $errors['firstName'] = ['message' => 'Not valid name'];
                }
            }

            // Validate Surname
            $isSurNameEmpty = StringHelper::isEmpty($inputs['surName']);
            if ($isSurNameEmpty) {
                $errors['surName'] = [ 'message' => 'Field cannot be empty' ];
            }

            if (!isset($errors['surName']) && empty($errors['surName'])) {
                $isSurNameContainsOnlyLetters = StringHelper::hasOnlyLetters($inputs['surName']);
                if (!$isSurNameContainsOnlyLetters) {
                    $errors['surName'] = ['message' => 'Not valid name'];
                }
            }

            // Validate Address
            $isAddressEmpty = StringHelper::isEmpty($inputs['address']);
            if ($isAddressEmpty) {
                $errors['address'] = [ 'message' => 'Field cannot be empty' ];
            }

            // Validate selected country
            if (!isset($inputs['countryId']) || empty($inputs['countryId'])) {
                $errors['countryId'] = [ 'message' => 'Select country' ];
            }

            if (!isset($errors['countryId']) && empty($errors['countryId'])) {
                $country = $this->country->getById($inputs['countryId']);
                if (!$country) {
                    $errors['countryId'] = [ 'message' => 'Invalid country' ];
                }
            }

            // Validate Post code
            $isPostCodeEmpty = StringHelper::isEmpty($inputs['postcode']);
            if ($isPostCodeEmpty) {
                $errors['postcode'] = [ 'message' => 'Field cannot be empty' ];
            }

            // Validate Phone number
            $isPhoneEmpty = StringHelper::isEmpty($inputs['phone']);
            if ($isPhoneEmpty) {
                $errors['phone'] = [ 'message' => 'Field cannot be empty' ];
            }

            if (!isset($errors['phone']) && empty($errors['phone'])) {
                $isPhoneMinLengthValid = strlen($inputs['phone']) >= PHONE_MIN_LENGTH;
                if (!$isPhoneMinLengthValid) {
                    $errors['phone'] = ['message' => 'Phone shoud be at least 7 digits'];
                }
            }

            if (!isset($errors['phone']) && empty($errors['phone'])) {
                $isPhoneContainsOnlyDigits = StringHelper::hasOnlyDigits($inputs['phone']);
                if (!$isPhoneContainsOnlyDigits) {
                    $errors['phone'] = ['message' => 'Phone must contains only digits'];
                }
            }

            // Validate Email address
            $isEmailEmpty = StringHelper::isEmpty($inputs['email']);
            if ($isEmailEmpty) {
                $errors['email'] = [ 'message' => 'Field cannot be empty' ];
            }

            if (!isset($errors['email']) && empty($errors['email'])) {
                $isEmailValid = StringHelper::isEmail($inputs['email']);
                if (!$isEmailValid) {
                    $errors['email'] = ['message' => 'Invalid email format'];
                }
            }

            if (!isset($errors['email']) && empty($errors['email'])) {
                $user = $this->user->findByEmail($inputs['email']);
                if ($user) {
                    $errors['email'] = [ 'message' => 'Email address is already used' ];
                }
            }
        } catch (\Exception $ex) {
            $this->log->error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }

        return $errors;
    }
}