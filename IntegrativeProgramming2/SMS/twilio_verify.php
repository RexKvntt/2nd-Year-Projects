<?php

require 'vendor/autoload.php';

use Twilio\Rest\Client;

function getTwilioCredentials()
{
    $sid = '[YourAccountSID]';
    $token = '[YourAuthToken]';
    $serviceSid = '[YourServiceSID]';

    if ($sid === '' || $serviceSid === '' || $token === '' || $token === '[AuthToken]') {
        die('Twilio credentials are incomplete.');
    }

    return array($sid, $token, $serviceSid);
}

function createTwilioClient()
{
    list($sid, $token) = getTwilioCredentials();
    return new Client($sid, $token);
}

function sendOtpViaTwilio($phoneNumber)
{
    if (!preg_match('/^\+63\d{10}$/', $phoneNumber)) {
        die('Invalid Philippine phone number. Please use the +63 format.');
    }

    list($sid, $token, $serviceSid) = getTwilioCredentials();

    try {
        $twilio = new Client($sid, $token);
        $verification = $twilio->verify->v2->services($serviceSid)
            ->verifications
            ->create($phoneNumber, 'sms');

        return !empty($verification->sid);
    } catch (\Exception $e) {
        die('SMS could not be sent. Error: ' . $e->getMessage());
    }
}

function verifyOtpViaTwilio($phoneNumber, $otpCode)
{
    if (!preg_match('/^\+63\d{10}$/', $phoneNumber)) {
        die('Invalid Philippine phone number. Please use the +63 format.');
    }

    list($sid, $token, $serviceSid) = getTwilioCredentials();

    try {
        $twilio = new Client($sid, $token);
        $verificationCheck = $twilio->verify->v2->services($serviceSid)
            ->verificationChecks
            ->create(array(
                'to' => $phoneNumber,
                'code' => $otpCode
            ));

        return $verificationCheck->status === 'approved';
    } catch (\Exception $e) {
        die('OTP verification failed. Error: ' . $e->getMessage());
    }
}
