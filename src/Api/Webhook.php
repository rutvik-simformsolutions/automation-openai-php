<?php

namespace OpenSource\AutomationOpenai\Api;
require_once("Services/ChatGPTService.php");
require_once("Services/GitService.php");
require_once("Services/BitbucketService.php");

use OpenSource\AutomationOpenai\Services\ChatGPTService;
use OpenSource\AutomationOpenai\Services\BitbucketService;

class Webhook 
{
    public function __construct()
    {
        $pageId = $_ENV['JIRA_PAGE'];
        $ChatGPTService = new ChatGPTService();
        $BitbucketService = new BitbucketService();
        shell_exec('./script.sh');
        $recentCommits = addslashes(json_encode("write change log documentation from this points. \n ".file_get_contents("output.txt")));
        $openAI = $ChatGPTService->openaiChat($recentCommits);
        $jiraPage = json_decode($BitbucketService->viewPage($pageId));
        echo $confluencePage = $BitbucketService->updatePage($pageId,$jiraPage->title,$jiraPage->version->number,$openAI);
    }
}