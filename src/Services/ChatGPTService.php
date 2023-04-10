<?php
namespace OpenSource\AutomationOpenai\Services;

class ChatGPTService
{
    public function openaiChat($prompt){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{
		  "model": "gpt-3.5-turbo",
		  "messages": [{"role": "user", "content": " '.$prompt.' "}]
		  }',
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "Authorization: Bearer " . $_ENV['API_KEY']
		  ),
		));
	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    if ($err) {
	        return "cURL Error #:" . $err;
	    } else {
	        $response_json = json_decode($response, true);
	        $doc_text = $response_json['choices'][0]['message']['content'];
	        return $doc_text;
	    }
    }
}