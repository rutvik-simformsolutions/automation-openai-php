<?php

namespace OpenSource\AutomationOpenai\Api;

require_once "Services/ChatGPTService.php";
require_once "Services/GitService.php";
require_once "Services/BitbucketService.php";

use OpenSource\AutomationOpenai\Services\BitbucketService;
use OpenSource\AutomationOpenai\Services\ChatGPTService;
use OpenSource\AutomationOpenai\Services\GitService;

class Webhook
{
    public function __construct()
    {
        if (isset($_ENV['PR_REVIEW'])) {
            $gitService = new GitService();
            $gitService->PRreview();
        } else {
            $chatGPTService = new ChatGPTService();
            $bitbucketService = new BitbucketService();
            shell_exec('./script.sh');
            $recentCommits = "write change log documentation from this points and remember these points are git commit message and make sure each point should be explained in descriptive manner. \n " . file_get_contents("output.txt");
            $openAI = $chatGPTService->openaiChat($recentCommits);
            $bitbucketService::generateConfluencePage($openAI, 'OpenAI Change Logs ' . date('d/m/y H:i'));

            $pattern = '/AI-\d+/';
            $taskIds = [];
            preg_match_all($pattern, file_get_contents("output.txt"), $matches);
            if (!empty($matches[0])) {
                $taskIds = $matches[0];
            }

            $tasks = json_decode($bitbucketService::searchJiraTasks("AI"));
            $taskStatus = [];
            foreach ($tasks->issues as $task) {
                $taskStatus[] = [
                    'key' => $task->key,
                    'status' => $task->fields->status->name,
                    'title' => $task->fields->summary,
                    'assignee' => @$task->fields->assignee->displayName,
                ];
            }
            $html = "<table border='1'>
                     <tbody>
                        <tr>
                            <th class='confluenceTh'> Task </th>
                            <th class='confluenceTh'> Developer </th>
                            <th class='confluenceTh'> Status </th>
                            <th class='confluenceTh'> PR </th>
                        </tr>";
            foreach ($taskStatus as $task) {
                $status = (in_array($task['key'], $taskIds)) ? "&#10004; pushed to production" : "";
                $html .= "<tr>
                            <td class='confluenceTd'> <a href='".$_ENV['TASK_URL']."/$task[key]' target='_blank'> $task[key] $task[title] </a> </td>
                            <td class='confluenceTd'> $task[assignee] </td>
                            <td class='confluenceTd'> $status </td>
                            <td class='confluenceTd'>  </td>
                          </tr>";
            }
            $html .= "</tbody> </table>";
            $bitbucketService::generateConfluencePage($html, 'OpenAI PCR ' . date('d/m/y H:i'));
        }
    }
}
