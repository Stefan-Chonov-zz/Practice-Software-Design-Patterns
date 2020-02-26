$(function() {
    $("#userForm").submit(function(event) {
        $inputValidationResults = validateInput($('#userForm').serializeArray());
        hideErrors();
        showErrors($inputValidationResults);
        if (!$.isEmptyObject($inputValidationResults)) {
            event.preventDefault();
            event.stopPropagation();
        }
    });

    $("#clearButton").on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('.formMessage').hide();
        hideErrors();

        $(':input','#userForm')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .prop('checked', false)
            .prop('selected', false);
    });

    function validateInput($inputs) {
        $result = {};

        $hasContry = false;
        $.each($inputs, function(key, input) {
            input['value'] = $.trim(input['value']);

            if (input['value'] === '' || input['value'].length === 0) {
                $result[input['name']] = [];
                $result[input['name']]['message'] = 'Field cannot be empty';
            }

            if (input['name'] === 'firstName' && !('firstName' in $result) && hasNumber(input['value'])) {
                $result[input['name']] = [];
                $result[input['name']]['message'] = 'Not valid name';
            }

            if (input['name'] === 'surName' && !('surName' in $result) && hasNumber(input['value'])) {
                $result[input['name']] = [];
                $result[input['name']]['message'] = 'Not valid name';
            }

            if (input['name'] === 'countryId') {
                $hasContry = true;
            }

            if (input['name'] === 'phone' && !('phone' in $result)) {
                if (input['value'].length < 7) {
                    $result[input['name']] = [];
                    $result[input['name']]['message'] = 'Phone shoud be at least 7 digits';
                }

                if (!$.isNumeric(input['value'])) {
                    $result[input['name']] = [];
                    $result[input['name']]['message'] = 'Phone can contain only numbers';
                }
            }

            if (input['name'] === 'email' && !('email' in $result) && !isEmail(input['value'])) {
                $result[input['name']] = [];
                $result[input['name']]['message'] = 'Email address is not valid address';
            }
        });

        if (!$hasContry) {
            $result['countryId'] = [];
            $result['countryId']['message'] = 'Select country';
        }

        return $result;
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function hasNumber(myString) {
        var regEx = /^[a-zA-Z]+$/;
        return !regEx.test(myString);
    }

    function hideErrors() {
        $('.errorMessage').hide();
    }

    function showErrors($errors) {
        $.each($errors, function(key, result) {
            $errorMessage = $('input[name="' + key + '"]').parent().next();
            if ($errorMessage.length === 0) {
                $errorMessage = $('select[name="' + key + '"]').parent().next();
            }

            $errorMessage.html(result['message']);
            $errorMessage.show();
        });
    }
});