<?php

namespace App\Helpers;

class DataSEOHelper
{

    protected $login;
    protected $password;
    protected $cred;


    public function __construct()
    {
        $this->login = env('DATAFORSEOLOGIN');
        $this->password  = env('DATAFORSEOPPASSWORD');
        $this->creds = base64_encode("$this->login:$this->password");
    }

    private function responce($data, $endpoint, $method = "POST")
    {
        $data_string = json_encode($data);

        $ch = curl_init('https://api.dataforseo.com/v3/'.$endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Basic ' . $this->creds,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    public function getBacklinks($data)
    {
        $result = $this->responce($data, 'backlinks/domain_intersection/live');

        $return = [];

        if ($result["status_code"] == 20000) {
            $return["status"] = 'Ok';
            $return['body'] = collect($result["tasks"][0]['result'][0]['items'])
                ->map(function ($item) use ($data) {

                    $itemResponce = collect($item['domain_intersection'])->first();

                    return [
                        'excluded_target' => json_encode($data[0]["exclude_targets"]) ,
                        'target_domain' => reset($data[0]["targets"]),
                        'referring_domain' => $itemResponce['target'],
                        'rank' => strval($itemResponce['rank']),
                        'backlinks' => strval($itemResponce['backlinks'])
                    ];
                });
        } else {
            $return['status'] = 'Error';
            $return['body'] = $result;
        }

        return $return;
    }
}
