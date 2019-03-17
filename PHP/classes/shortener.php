<?php

class Do0 {
    private $api_key      = '';
    private $api_url      = 'https://do0.ir/post/';
    private $api_version  = '';
    private $version_file = __DIR__.'version.txt';  // path of the version file

    public function __construct($key = ''){
        $this->api_key = $key;
    }

    public function buildURL(){
        if( !file_exists($this->version_file) ){
            $this->versionForceUpdate();
        } else {
            $file = fopen($this->version_file, 'r');
            $latest_version = fread($file, filesize($this->version_file));
            fclose($file);
            $this->api_version = $latest_version;
        }

        $this->api_url = $this->api_url.$this->api_key.'/'.$this->api_version.'/code';
        return $this;
    }

    public function getApiURL(){
        return $this->api_url;
    }

    public function getApiVersion(){
        return $this->api_version;
    }

    public function versionForceUpdate(){
        $latest_version = null;
        $latest_version = json_decode($this->request('https://do0.ir/api/version'));
        $latest_version = $latest_version->version;
        if(!empty($latest_version) && is_numeric($latest_version)) {
            $this->api_version = $latest_version;
            $file = fopen($this->version_file, 'w');
            fwrite($file, $latest_version);
            fclose($file);
        } else throw new ErrorHandler("Bad version code retrieved from the server!");
        return $this;
    }

    public function request($url = '', $type = 'get', $data = []){
        $req = curl_init();
        if($type === 'get'){
            curl_setopt_array($req, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $url));
        } else {
            curl_setopt_array($req, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
            ));
        }
        $res = curl_exec($req);
        curl_close($req);
        return $res;
    }

    public function shorten($url = '', $name = ''){
        if(empty($url)) throw new ErrorHandler("URL is not entered!");
        else {
            $res = json_decode($this->request($this->getApiURL(), 'POST', ["link" => $url, "name" => $name]));
            if($res->error === "Wrong API version!"){
                $this->versionForceUpdate()->buildURL();
                $res = json_decode($this->request($this->getApiURL(), 'POST', ["link" => $url, "name" => $name]));
            }
            return $res;
        }
    }

}

class ErrorHandler extends Exception {
    public $message = '';

    public function __construct($txt){
        $this->message = $txt;
    }
    public function show(){
        return $this->message;
    }
}
?>