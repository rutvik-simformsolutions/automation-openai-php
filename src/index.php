<?php
namespace OpenSource\AutomationOpenai;

require_once ('../vendor/autoload.php');

require_once("Api/Webhook.php");

use OpenSource\AutomationOpenai\Api\Webhook;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('../.env');

// overwrites existing env variables
$dotenv->overload('../.env');

// loads .env, .env.local, and .env.$APP_ENV.local or .env.$APP_ENV
$dotenv->loadEnv('../.env');

$webhook = new Webhook();