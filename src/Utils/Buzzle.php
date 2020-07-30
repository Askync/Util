<?php

namespace Askync\Utils\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Buzzle
{

    private $url;
    private $method;
    private $header = [];
    private $params;
    private $request;
    private $logEnabled;
    private $rawRequest;

    function __construct($config=['log' => false])
    {
        $this->request = app()->request;
        $this->rawRequest = false;
        $this->logEnabled = $config['log'];
    }

    public function logIs($log=true){
        $this->logEnabled = $log;
        return $this;
    }
    public function raw($raw=true)
    {
        $this->rawRequest = $raw;
        return $this;
    }

    public function __call($method, array $args)
    {
        $this->method = strtolower($method);
        $this->url = $args[0] ?? false;
        $this->header = $args[1] ?? [];
        $this->params = $args[2] ?? [];

        if( !in_array(strtolower($this->method), ['post', 'get', 'put', 'patch', 'delete', 'head', 'options']) )
            return (object) [ 'error' => 'invalid method' ];

        return $this->buzz();
    }

    private function buzz()
    {
        $client = new Client(['http_errors' => false]);
        $this->header['Content-Type'] = (isset($this->header['Content-Type'])) ? $this->header['Content-Type'] : 'application/json';
        $this->header['Accept'] = (isset($this->header['Accept'])) ? $this->header['Accept'] : 'application/json';
        if(isset($this->header['Authorization'])) {
            $this->header['Authorization'] = (isset($this->header['Authorization'])) ? $this->header['Authorization'] : 'Basic ==';
        }
        if(isset($this->header['encoding'])) {
            $this->header['encoding'] = (isset($this->header['encoding'])) ? $this->header['encoding'] : 'application/json';
        }

        $options['headers'] = $this->header;

        if($this->header['Content-Type'] != 'application/json') {
            $options['form_params'] = $this->params;
        }
        else {
            $options['json'] = $this->params;
        }

        try {
            if($this->rawRequest) {
                return $client->{$this->method}($this->url, $options)->getBody()->getContents() ?? '{"error":true}';
            }

            $response = $client->{$this->method}($this->url, $options);
            $rawBody = $response->getBody()->getContents();

            // logging request
            if($this->logEnabled) {

            }

            $body = json_decode($rawBody);
            if(!$body) {
                $body = (object)[
                    'error'=> 1,
                    'trace' => [
                        'content' => $rawBody
                    ]
                ];
            }

            return $body;

        } catch (\Exception $e) {
            if($this->logEnabled) {

            }
            return null;
        }
    }

}
