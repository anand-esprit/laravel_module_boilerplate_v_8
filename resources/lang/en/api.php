<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during API response for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'references' => [
        'INVALID_EMAIL_ID' => 'Invalid email type reference',
        'INVALID_USER_ID' => 'Invalid user reference',
    ],

    'links' => [
        'LINKED_USER' => 'Unable to delete because it is associated with user',
    ],

    'notifications' => [
        'INVALID_API_KEY' => 'Access denied, invalid api key',
        'INVALID_PLATFORM' => 'Access denied, invalid platform',
        'LOGIN_FAILED' => 'Username/Password doesn’t match',
        'AUTH_FAILED' => 'Access denied, invalid authorization code',
        'SESSION_EXPIRED' => 'Access denied, your session is expired',
        'ACCOUNT_DISABLED' => 'Your account is disabled, Please contact your administrator.',
        'LICENSE_EXPIRED' => 'Your company license is expired, Please contact support.',
        'INVALID_URL' => 'Invalid URL or unable to reach to the required resource',
        'OTHER_ERROR' => 'Unexpected error, please contact administrator',
        'NO_PERMISSION' => 'You have no permission.',
        'INIT_SUCCESSFULL' => 'Successfully initialized',        
        'LOGIN_SUCCESSFULL' => 'Successfully Logged In',
        'LOGOUT_SUCCESSFULL' => 'Successfully Logged Out',
        'INVALID_IP' => 'Access denied, invalid Ip',
    ],

    'common' => [
        'NAME_REQUIRED' => 'It cannot be blank',
        'USERNAME_OR_EMAIL_REQUIRED' => 'Email or Username cannot be blank',
        'USERNAME_EXISTS' => 'Your Username is not Found. Please Contact Your administrator.',
        'LOGIN_ID_REQUIRED' => 'It cannot be blank',
        'LOGIN_ID_MIN' => 'It must have at least 8 characters',
        'PHONE_EXISTS' => 'Your Phone Number is not Found. Please Contact Your administrator.',        
        'EMAIL_REQUIRED' => 'Email cannot be blank',
        'EMAIL_INVALID' => 'Email is not valid',
        'EMAIL_EXISTS' => 'Your Email is not Found. Please Contact Your administrator.',
        'PASSWORD_REQUIRED' => 'Password cannot be blank',
        'PASSWORD_MIN_LENGTH_ERROR' => 'Password must have at least eight characters',
        'PASSWORD_REGEX' => 'Password must have one lowercase, one uppercase, one digit and one special character(_, -, @).',        
        'EXISTING_PASSWORD_REQUIRED' => 'Existing Password cannot be blank',
        'EXISTING_PASSWORD_NOT_MATCHED' => 'Must match with existing password',
        'NEW_PASSWORD_REQUIRED' => 'New Password cannot be blank',
        'NEW_PASSWORD_MIN_LENGTH_ERROR' => 'New Password must have at least eight characters',
        'NEW_PASSWORD_REGEX' => 'New Password must have one lowercase, one uppercase, one digit and one special character(_, -, @).',
        'NEW_PASSWORD_CAN_NOT_BE_SAME' => 'New Password Must not be same as existing password',
        'EMAIL_SENT' => 'Email sent successfully',
        'SENT_OTP_SUCCESSFULLY' => 'Sent an email with OTP.',
        'RESET_PASSWORD_SUCCESSFULLY' => 'Your password is reset successfully',
        'EMAIL_IS_INACTIVE' => 'Sorry, this Email is inactive',
        'OTP_REQUIRED' => 'OTP cannot be blank',
        '6_DIGITS_OTP' => 'OTP should be in 6 digits',
        'OTP_NOT_AVAILABLE' => 'Sorry, you have not requested for forgot password yet',
        'OTP_INVALID' => 'Sorry, your OTP is not valid',
        'NAME_MIN_LENGTH_ERROR' => 'Name must have at least 3 characters',
        'NAME_MAX_LENGTH_ERROR' => 'Maximum length for name is 50 characters',
        'NAME_MAX_15_LENGTH_ERROR' => 'Maximum length for name is 15 characters',
        'CREATED' => 'Successfully Created',
        'UPDATED' => 'Successfully Updated',
        'REOPENED' => 'Successfully Reopened',
        'CLOSED' => 'Successfully Closed',
        'SCOPE_UPDATED' => 'Scope Successfully updated',
        'DELETED' => 'Successfully Deleted',
        'ACTIVATED' => 'Successfully Activated',
        'DEACTIVATED' => 'Successfully Deactivated',
        'OVERRIDE_TRUE' => 'Successfully allow to override',
        'OVERRIDE_FALSE' => 'Successfully disallow to override',
        'GENERATED' => 'Successfully Generated',
        'SHORT_NAME_REQUIRED'  => 'Short Name cannot be blank',
        'PRIMARY_NUMBER_REQUIRED'  => 'It cannot be blank',
        'PRIMARY_NUMBER_LENGTH_ERROR_MIN'  => 'It should be a 10 digit phone number',
        'PRIMARY_NUMBER_LENGTH_ERROR_MAX'  => 'Maximum length is 20 characters',
        'PRIMARY_NUMBER_DUPLICATED'  => 'It is already exists',
        'ALTERNATE_NUMBER_LENGTH_ERROR_MIN'  => 'It should be a 10 digit phone number',
        'ALTERNATE_NUMBER_LENGTH_ERROR_MAX'  => 'Maximum length is 20 characters',
        'CONTACT_NUMBER_REQUIRED'  => 'Contact number cannot be blank',
        'CONTACT_NUMBER_LENGTH_ERROR_MIN'  => 'Minimum length for contact number is 10 characters',
        'CONTACT_NUMBER_LENGTH_ERROR_MAX'  => 'Maximum length for contact number is 20 characters',
        'CONTACT_NUMBER_DUPLICATED'  => 'Contact number already exists',
        'INWARDED' => 'Successfully Inwarded',
        'ROLLBACK_INWARD' => 'Successfully rollback',
        'TRANSFERED' => 'Successfully transfer',
        'BOOKING_CONFIRMED' => 'Successfully Confirmed',
        'VERIFIED' => 'Verified',
        'DEVICE_TOKEN_REQUIRED' => 'Device Token Required',
        'WIDGET_ORDER_REQUIRED' => 'Widget Order Position Object Required',
    ],

    'auth' => [
        'USER_INACTIVE' => 'Your account is disabled, please contact your administrator.',
        'USER_NOT_EXIST' => 'User is not Found. Please Contact Your administrator.',
    ],

];