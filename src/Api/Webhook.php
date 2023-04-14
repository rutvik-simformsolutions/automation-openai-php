<?php

namespace OpenSource\AutomationOpenai\Api;
require_once("Services/ChatGPTService.php");
require_once("Services/GitService.php");
require_once("Services/BitbucketService.php");

use OpenSource\AutomationOpenai\Services\ChatGPTService;
use OpenSource\AutomationOpenai\Services\BitbucketService;
use OpenSource\AutomationOpenai\Services\GitService;

class Webhook 
{
    public function __construct()
    {
        if (isset($_ENV['PR_REVIEW'])) {
            $GitService = new GitService();

            $GitService->PRreview();
        }
        else {
            //$pageId = $_ENV['BITBUCKET_PAGE'];
            $ChatGPTService = new ChatGPTService();
            $BitbucketService = new BitbucketService();
            shell_exec('./script.sh');
            $recentCommits = "write change log documentation from this points. \n ".file_get_contents("output.txt")." before providing output please consider the points for formatting, first do not include any heading or title, secondly use && as number for each point, third the number of output points should be equal to number of points provided to you and last every point should be explained in descriptive manner and there should be single line break after each point.";
            $openAI = $ChatGPTService->openaiChat($recentCommits);
            $changeLog = array_filter(explode('&&', $openAI), fn($value) => !is_null($value) && $value !== '');
            $html = "<table border='1'> <tbody> <tr> <th class='confluenceTh'>Task</th> <th class='confluenceTh'> Status </th> </tr>";
            foreach ($changeLog as $log) {
                $html .= "<tr> <td class='confluenceTd'>$log</td> <td class='confluenceTd'> &#10004; </td> </tr>";
            }
            $html .= "</tbody> </table>";
            echo $BitbucketService::generateConfluencePage($html,'OpenAI Change Logs '.date('d/m/y H:i'));
        }
    }
}