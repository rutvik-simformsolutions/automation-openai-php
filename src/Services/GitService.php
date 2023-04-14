<?php
namespace OpenSource\AutomationOpenai\Services;

class GitService
{
    public function PRreview()
    {
        $access_token = $_ENV['PERSONAL_GITHUB_TOKEN'];
        $repo = $_ENV['OWNER_AND_REPO_NAME'];
        $number = $_ENV['PR_NUMBER'];

        // Get pull request details
        $pr_url = "https://api.github.com/repos/$repo/pulls/$number";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $pr_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] =  "User-Agent: my-app";
        $headers[] = "Authorization: Bearer $access_token";
        $headers[] = 'Accept: application/vnd.github.v3.diff';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        echo $result;

        // Set the comment text
        $ChatGPTService = new ChatGPTService();

        $pormpt = "please review below code and provide overall comment  \n" . $result;  
        $comment = $ChatGPTService->openaiChat($pormpt);

        // Set the API endpoint
        $comment_pr_url = "https://api.github.com/repos/$repo/issues/$number/comments";

        // Set the headers
        $headers = array(
            "User-Agent: my-app",
            "Authorization: Bearer " . $access_token,
            "Accept: application/vnd.github.v3+json",
            "Content-Type: application/json",
        );

        // Set the data to send
        $data = array(
            "body" => $comment,
        );

        // Create a new cURL resource
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $comment_pr_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Send the request and get the response
        $response = curl_exec($ch);

        // Close the cURL resource
        curl_close($ch);

        echo $response;
    }
}