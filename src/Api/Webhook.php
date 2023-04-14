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
            $gitService = new GitService();

            $gitService->PRreview();
        }
        else {
            //$pageId = $_ENV['BITBUCKET_PAGE'];
            $chatGPTService = new ChatGPTService();
            $bitbucketService = new BitbucketService();
            shell_exec('./script.sh');
            //$recentCommits = "Write change log documentation from this points. \n ".file_get_contents("output.txt")." before providing output please consider the points for formatting, first do not include any heading or title, secondly use && as number for each point, third the number of output points should be equal to number of points provided to you and last every point should be explained in descriptive manner and there should be single line break after each point.";
            $recentCommits = "Write change log documentation from this points. \n ".file_get_contents("output.txt");
            //print_r($recentCommits);
            //echo "---------------1---------------------";

            $openAI = $chatGPTService->openaiChat($recentCommits);
            //print_r($openAI);
            //echo "-----------------2-------------------";
            //$changeLog = array_filter(explode('&&', $openAI), fn($value) => !is_null($value) && $value !== '');
            //print_r($changeLog);
            //echo "-----------------3-------------------";
            /*$html = "<table border='1'> <tbody> <tr> <th class='confluenceTh'>Task</th> <th class='confluenceTh'> Status </th> </tr>";
            foreach ($changeLog as $log) {
                $html .= "<tr> <td class='confluenceTd'>$log</td> <td class='confluenceTd'> &#10004; </td> </tr>";
            }
            $html .= "</tbody> </table>";*/
            if (is_array($openAI)) {
                foreach($openAI as $log) {
                    $html .= $log;
                }
            } else {
                $html = $openAI;
            }
            
            $bitbucketService::generateConfluencePage($html,'OpenAI Change Logs '.date('d/m/y H:i'));
        }
    }
}