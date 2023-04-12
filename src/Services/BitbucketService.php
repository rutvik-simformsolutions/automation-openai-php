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
    
    /**
     * @param String $content
     * @param String|null $title
     * @return bool|string
     */
    public static function generateConfluencePage(String $content, String $title = null)
    {
        try {
            #checking for incoming data
            if (empty($content))
                return "Oh common! you don't want to create empty confluence page !!";

            #bit bucket confluence api configuration
            $bitbucketEmail = $_ENV["BITBUCKET_EMAIL"];
            $bitbucketToken = $_ENV["BITBUCKET_TOKEN"];
            $bitbucketUrl = $_ENV["BITBUCKET_URL"];
            $bitbucketSpacekey = $_ENV["BITBUCKET_SPACEKEY"];

            if (empty($bitbucketEmail) || empty($bitbucketToken) || empty($bitbucketUrl) || empty($bitbucketSpacekey))
                return "Error : Not Enough Configuration Data Available for Bitbucket Confluence Page";

            #insilizing Curl Request
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $bitbucketUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            #setingup the headers
            $headers = [];
            $headers[] = 'Content-Type: application/json';
            $headers[] = "Authorization: Basic " . base64_encode("$bitbucketEmail:$bitbucketToken");
            $data = [
                "type" => "page",
                "title" => $title ?? "New Confluence Page",
                "space" => ["key" => $bitbucketSpacekey],
                "body" => ["storage" => ["value" => $content, "representation" => "storage"]]
            ];
            $jsonData = json_encode($data);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            #Sendting the curl request
            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                throw new \Exception("Curl Error :" . $err);
            } else {
                return $result;
            }

        } catch (\Exception $exception) {
            return "Error : Curl Error". PHP_EOL . $exception->getMessage();
        }
    }
}