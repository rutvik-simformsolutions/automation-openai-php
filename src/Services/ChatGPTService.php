<?php
namespace OpenSource\AutomationOpenai\Services;

class ChatGPTService
{
	public static function openaiChat(String $prompt)
    {
        try {

            if (empty($prompt))
                return "empty prompt!";

            if (!isset($_ENV['API_KEY']) || empty($_ENV['API_KEY']))
                return "Error : openaiKey not available!";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = [];
            $headers[] = 'Content-Type: application/json';
            $headers[] = "Authorization: Bearer " . $_ENV['API_KEY'];
            $data = [
                "model" => "gpt-3.5-turbo",
                "messages" => array(["role" => "user", "content" => $prompt])
            ];
            $jsonData = json_encode($data);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                throw new \Exception("Curl Error :" . $err);
            } else {
                $response = json_decode($result, true);
	        	$output = $response['choices'][0]['message']['content'];
	        	return $output;
            }

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}