<?php
namespace OpenSource\AutomationOpenai\Services;

class BitbucketService
{
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