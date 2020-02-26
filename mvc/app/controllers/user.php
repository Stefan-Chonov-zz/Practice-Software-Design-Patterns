<?php

//namespace Controllers;

use Core\Controller;
use Core\Log;
use Models\UserRepository;
use Models\CountryRepository;

define("PHONE_MIN_LENGTH", 7);

class User extends Controller
{
    private $user;
    private $country;

    public function __construct()
    {
        $this->user = new UserRepository();
        $this->country = new CountryRepository();
    }

    public function index()
    {
        header("Location: create");
    }

    public function create()
    {
        try {
            $response['countries'] = $this->country->getAll();
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $userFormValidationResult = self::validateUserFormInput($_POST);
                if (!$userFormValidationResult['form']['hasError']) {
                    $id = $this->user->create($_POST);
                    if ($id > 0) {
                        $userFormValidationResult['form']['message'] = "User is stored successfully";
                        Log::info(json_encode($_POST));
                    }
                } else {
                    $userFormValidationResult['form']['data'] = $_POST;
                    $userFormValidationResult['form']['message'] = "Invalid form data";
                    Log::warning('Invalid input from user: ' . PHP_EOL . json_encode($response['form']['errors'], JSON_PRETTY_PRINT));
                }

                $response = array_merge($response, $userFormValidationResult);
            }

        } catch (Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTrace());
        }
        parent::view('user/create', $response);
    }

    /**
     * Validate user inputs
     * @param $inputs
     * @return array
     * @throws \Exception
     */
    public function validateUserFormInput($inputs)
    {
        try {
            $userForm = [
                "form"=> [
                    "message" => "",
                    "hasError" => FALSE
                ],
            ];

            $userForm['form']['errors'] = self::checkFormInputsForEmptyValues($inputs);
            foreach ($userForm['form']['errors'] as $key => $value) {
                $userForm['form']['errors'][$key] = [ 'message' => 'Field cannot be empty' ];
            }

            $isFirstNameContainsOnlyLetters = self::hasOnlyLeters($inputs['firstName']);
            if (!isset($userForm['form']['errors']['firstName']) && !$isFirstNameContainsOnlyLetters) {
                $userForm['form']['errors']['firstName'] = [ 'message' => 'Not valid name' ];
            }

            $isSurNameContainsOnlyLetters = self::hasOnlyLeters($inputs['surName']);
            if (!isset($userForm['form']['errors']['surName']) && !$isSurNameContainsOnlyLetters) {
                $userForm['form']['errors']['surName'] = [ 'message' => 'Not valid name' ];
            }

            if (!isset($userForm['form']['errors']['countryId']) && (!isset($inputs['countryId']) || empty($inputs['countryId']))) {
                $userForm['form']['errors']['countryId'] = [ 'message' => 'Select country' ];
            }

            $isPhoneSet = isset($inputs['phone']);
            if (!isset($userForm['form']['errors']['phone']) && $isPhoneSet) {
                $isPhoneMinLengthValid = strlen($inputs['phone']) >= PHONE_MIN_LENGTH;
                if (!$isPhoneMinLengthValid) {
                    $userForm['form']['errors']['phone'] = [ 'message' => 'Phone shoud be at least 7 digits' ];
                }

                $isPhoneContainsOnlyDigits = ctype_digit($inputs['phone']);
                if (!isset($userForm['form']['errors']['phone']) && !$isPhoneContainsOnlyDigits) {
                    $userForm['form']['errors']['phone'] = [ 'message' => 'Phone can contain only numbers' ];
                }
            }

            $isEmailValid = filter_var($inputs['email'], FILTER_VALIDATE_EMAIL);
            if (!isset($userForm['form']['errors']['email']) && !$isEmailValid) {
                $userForm['form']['errors']['email'] = [ 'message' => 'Invalid email format' ];
            }

            $user = $this->user->findByEmail($inputs['email']);
            if (!isset($userForm['form']['errors']['email']) && $user) {
                $userForm['form']['errors']['email'] = [ 'message' => "Email address is already used" ];
            }

            if (count($userForm['form']['errors']) > 0) {
                $response['form']['hasError'] = TRUE;
            }
        } catch (\Exception $ex) {
            throw $ex;
            Log::error($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }

        return $userForm;
    }

    /**
     * Returns list  input is not set or empty
     * @param $inputs
     * @return array
     */
    private function checkFormInputsForEmptyValues($inputs)
    {
        $results = [];

        foreach ($inputs as $key => $value) {
            if (!isset($value) || empty($value)) {
                $results[$key] = TRUE;
            }
        }

        return $results;
    }

    /**
     * @param $string
     * @return bool
     */
    private function hasOnlyLeters($string)
    {
        $hasSpecialChars = preg_match('/[#$%!^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $string);
        $hasDigits = preg_match('/\\d/', $string) > 0;

        return !$hasSpecialChars && !$hasDigits;
    }
}