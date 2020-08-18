<?php

namespace Askync\Utils\HttpUtils;

class Client
{
    protected $options = [
        'method' => null,
        'url' => null,
        'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
        'params' => [],
        'serialized_header' => [],
    ];

    /*
     * config object
     * expectJson, throwErrorOnNotJson
     * header Content-Type:multipart/form-data
     */
    private $config;

    public function __construct(array $config=['expectJson' => true], array $options = [])
    {
        $this->config = new \stdClass();
        foreach ($config as $key=>$value) {
            $this->config->{$key} = $value;
        }
    }

    public function setConfig($configName, $value)
    {
        $this->config->{$configName} = $value;
    }

    public function getConfig($configName)
    {
        return (isset($this->config->{$configName})) ? $this->config->{$configName} : null;
    }

    public function setOptions(string $optionName=null, $value = null)
    {
        $this->options[$optionName] = $value;
        return $this;
    }
    public function getOptions(string $optionName=null)
    {
        if(isset($this->options[$optionName])) {
            return $this->options[$optionName];
        }
        return null;
    }

    public function __call($method, array $args)
    {
        // call method

        // args[0] = url,
        // args[1] = headers,
        // args[2] = params,

        $this->setOptions('method', $method);

        if(!isset($args[0])) {
            throw new \Exception('Please provide url');
        }
        $this->setOptions('url', $args[0]);

        if(isset($args[1]) && is_array($args[1])) {
            $allHeaders = $this->getOptions('headers');
            foreach ($args[1] as $key => $arg) {
                $allHeaders[$key] = $arg;
            }
            $this->setOptions('headers', $allHeaders);
        }
        if(isset($args[2])) {
            $this->setOptions('params', $args[2]);
        }

        if( !in_array(strtolower($method), ['post', 'get', 'put', 'patch', 'delete', 'head', 'options']) )
            return (object) [ 'error' => 'invalid method' ];

        $serialized=[];
        foreach ($this->getOptions('headers') as $key=>$value) {
            $serialized[] = sprintf('%s: %s', $key, $value);
        }
        $this->setOptions('serialized_header', $serialized);

        return $this->buzz();
    }

    private function buzz()
    {
        $call = 'call'. $this->getOptions('method');
        if(!method_exists($this, $call)) {
            throw new \Exception(sprintf('Method call %s does not exists', $this->getOptions('method')));
        }
        $response = $this->{$call}();
        if ($this->getConfig('expectJson')) {
            $tryJson = json_decode($response);
            if(!$tryJson) {
                if($this->getConfig('throwErrorOnNotJson')) {
                    throw new \Exception(sprintf('non json response received from %s', $this->getOptions('url')));
                }
                return (object)['error' => '-1', 'message' => 'a non json response received'];
            }
            $response = $tryJson;
            unset($tryJson);
        }

        return $response;
    }

    /*
     * params = array(
     *   'name[0]' => '@' . realpath('first.jpg'),
     *   'name[1]' => '@' . realpath('second.jpg')
     * ) to upload file
     */
    private function callpost()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlObj, CURLOPT_POST, true);
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }

            return $response;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function callpatch()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curlObj, CURLOPT_POST, true);
            curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function callput()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curlObj, CURLOPT_POST, true);
            curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function calldelete()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function callhead()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'HEAD');
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function calloptions()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $this->getOptions('params'));

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    private function callget()
    {
        try {
            $curlObj = curl_init($this->getOptions('url'));
            curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curlObj, CURLOPT_HTTPHEADER, $this->getOptions('serialized_header'));

            $response = curl_exec($curlObj);
            $error    = curl_error($curlObj);
            $errno    = curl_errno($curlObj);

            if (is_resource($curlObj)) {
                curl_close($curlObj);
            }

            if (0 !== $errno) {
                throw new \RuntimeException($error, $errno);
            }

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
