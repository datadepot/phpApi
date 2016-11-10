<?php
/*
DataDepot.io php API
GitHub repo:
https://github.com/datadepot/phpApi
*/
class Datadepot
{
    private $api_id = '';
    private $api_key = '';
    private $site_id = '';

    public function __construct($api_id, $api_key, $site_id)
    {
        $this->api_id = $api_id;
        $this->api_key = $api_key;
        $this->site_id = $site_id;
    }

    public function getNewUid()
    {
        $answ = $this->request('https://data.datadepot.io/s2s/getuid', [
            'api_id' => $this->api_id,
            'api_key' => $this->api_key,
            'site_id' => $this->site_id,
        ], true, "POST");
        if ($answ['status'] == 'ok') {
            return (string) $answ['res']['data']['device_id'];
        } else {
            throw new Exception("DD: fetch new UID", 1);
        }
    }

    public function getUid()
    {
        return isset($_COOKIE['dduaef7ho7aez0ie8o']) ? $_COOKIE['dduaef7ho7aez0ie8o'] : '';
    }

    public function postEvent($name, $device_id, $opts)
    {
        $answ = $this->request('https://data.datadepot.io/s2s/postevent', [
            'api_id' => $this->api_id,
            'api_key' => $this->api_key,
            'site_id' => $this->site_id,
            'event' => array_merge([
                'name' => $name,
                'site_id' => $this->site_id,
                'device_id' => $device_id], $opts
            ),
        ], true, "POST");
        if ($answ['status'] == 'ok') {
            return true;
        } else {
            throw new Exception("DD: post event", 1);
        }
    }

    private function request($url, $request, $decodeResult = true, $httpMethod = "GET", $req_num = 0)
    {
        if (!is_string($request)) {
            $json_request = json_encode($request);
        } else {
            $json_request = $request;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpMethod);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json_request)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);
        $raw_res = curl_exec($ch);
        curl_close($ch);
        unset($json_request);
        unset($request);
        unset($ch);
        if ($decodeResult) {
            try {
                return ['status' => "ok", "res" => json_decode($raw_res, true)];
            } catch (Exception $e) {
                if ($req_num < 3) {
                    sleep(1);
                    return $this->request($url, $request, $decodeResult, $httpMethod, $req_num + 1);
                } else {
                    return ['error' => 'http'];
                }
            }
        } else {
            return $raw_res;
        }
    }
}
