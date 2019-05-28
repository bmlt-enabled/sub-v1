<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

try {
    $twilioClient = new Client($twilio_account_sid, $twilio_auth_token);
} catch (\Twilio\Exceptions\ConfigurationException $e) {
    error_log("Missing Twilio Credentials");
}
