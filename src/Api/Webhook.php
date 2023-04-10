<?php

namespace OpenSource\AutomationOpenai\Api;
require_once("Services/ChatGPTService.php");
require_once("Services/GitService.php");

use OpenSource\AutomationOpenai\Services\ChatGPTService;

class Webhook 
{
    public function __construct()
    {
        $ChatGPTService = new ChatGPTService();
        shell_exec('./script.sh'); // execute git log script file - not working on local machine
        $recentCommits = addslashes(json_encode("write change log documentation from below points: \n ".file_get_contents("output.txt")));
        print_r($ChatGPTService->openaiChat($recentCommits));
    }
}