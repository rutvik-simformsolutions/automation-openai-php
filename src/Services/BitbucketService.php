<?php
namespace OpenSource\AutomationOpenai\Services;

class BitbucketService
{

	public function authToken(){
		
		return base64_encode($_ENV['JIRA_USERNAME'].":".$_ENV['JIRA_TOKEN']);
	}

	public function viewPage($pageId){
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $_ENV['JIRA_HOST'].'/wiki/rest/api/content/'.$pageId,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic '.$this->authToken(),
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function updatePage($pageId,$title,$version,$content){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $_ENV['JIRA_HOST'].'/wiki/rest/api/content/'.$pageId,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_POSTFIELDS =>'{
		    "id": "'.$pageId.'",
		    "type": "page",
		    "status": "current",
		    "title": "'.substr($title, 0, -3).' v'.$version.'",
		    "version": {
		        "by": {
		            "type": "known"
		        },
		        "number": "'.((int)$version + 1).'"
		    },
		    "body": {
		        "storage": {
		            "representation": "storage",
		            "value": '.json_encode($content).'
		        }
		    }
		  }',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic '.$this->authToken(),
		    'Content-Type: application/json'
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
    
}