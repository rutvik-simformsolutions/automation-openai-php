<?php

namespace OpenSource\AutomationOpenai\Api;

class Webhook 
{
    public function __construct()
    {
        echo "Webhook Called";
        echo $_ENV['PROJECT_NAME'];
    }
}