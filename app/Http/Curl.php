<?php
namespace App\Http;
class Curl
{
    public $curl;
    public $response = null;
    public $response_header = null;
    public $header = ['charset=utf-8'];

    public function __construct() {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL fail!');
        }
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($this->curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 60);
    }
    public function get($url) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_POST, FALSE);
        $response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response = substr($response, $header_size);
    }
    public function getold($url) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, FALSE);
        $this->response = curl_exec($this->curl);
    }
    public function gzip() {
        curl_setopt($this->curl, CURLOPT_ENCODING, 'gzip');
    }
    public function post($url,$data = array()) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response = substr($response, $header_size);
    }
    public function postING($url,$data = array()) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,($data));
        $response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response = substr($response, $header_size);
    }
    public function postold($url,$data = array()) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $this->response = curl_exec($this->curl);
    }
    public function setAuthorization($account,$password){
        curl_setopt($this->curl, CURLOPT_USERPWD,'$account:$password');
    }
    public function setHeader($data) {
        $this->header = array_merge($this->header, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
    }
    public function setHeaderold($data) {
        curl_setopt($this->curl,CURLOPT_HTTPHEADER, $data);
    }
    public function https($status) {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST,$status);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER,$status);
    }
    public function setReffer($url) {
        curl_setopt($this->curl, CURLOPT_REFERER, $url);
    }
    public function xml($url,$data) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER,array("X-Forwarded-For: 52.198.18.88"));   //假冒别人的ip
        // curl_setopt($this->curl,CURLOPT_HTTPHEADER,array("X-Forwarded-For: 134.159.102.210"));   //假冒别人的ip
        $this->response = curl_exec($this->curl);
    }
    public function json($url,$data) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        $this->header = array_merge($this->header, array('Content-Type: application/json;','Content-Length: '.strlen($data)));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response = substr($response, $header_size);
    }
    public function json_nh($url,$data) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response = substr($response, $header_size);
    }
    public function jsonold($url,$data,$header=null) {
        if ($header != null) {
            curl_setopt($this->curl,CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $this->response = curl_exec($this->curl);
    }
    public function info() {
        return curl_getinfo($this->curl);
    }
    public function info_redirect() {
        return curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
    }
    public function setTimeOut($sec){
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $sec);
    }
    public function setCookie($array) {
        $cookie = "";
        foreach ($array as $v) {
            $cookie .= $v.";";
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Cookie: ".$cookie));
    }
    public function setUseragent($agentname){
        curl_setopt($this->curl, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($this->curl, CURLOPT_USERAGENT,$agentname);
    }
}

?>