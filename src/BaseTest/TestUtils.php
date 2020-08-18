<?php
namespace Askync\Utils\BaseTest;
use Laravel\Lumen\Http\Request as LumenRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait TestUtils
{
    protected $requestHeader;
    protected $requestData;
    public function printResponse($data=null, $die = false)
    {
        if(!$data)
            $data = $this;

        $function = 'dump';
        if($die)
            $function = 'dd';

        try {
            if( $content = json_decode( $data->response->getContent(), true)) {
                $function($content);
            }
            else {
                $function( $data->response->getContent() );
            }
        }
        catch (\Exception $e)
        {
            echo $e->getMessage() . "\n" . $e->getTraceAsString();
        }
        return $this;
    }

    public function displaySkeleton($display=false)
    {
        if($display || env('PHPUNIT_DEBUG', false)) {
            $data = json_decode($this->response->getContent(), true);
            dump([
                'url' => $this->currentUri,
                'error' => (isset($data['error'])) ? $data['error'] : -1,
                'body' => $this->requestData,
                'response' => (isset($data['data'])) ? $data['data'] : [],
            ]);
        }
        return $this;
    }


    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $this->requestHeader = $this->app['request'];
        $this->requestData = $parameters;

        $this->currentUri = $this->prepareUrlForRequest($uri);

        $symfonyRequest = SymfonyRequest::create(
            $this->currentUri, $method, $parameters,
            $cookies, $files, $server, $content
        );

        $this->app['request'] = LumenRequest::createFromBase($symfonyRequest);

        return $this->response = $this->app->prepareResponse(
            $this->app->handle($this->app['request'])
        );
    }

    public function printTestName($camelCaseName)
    {
        $this->runnerTestSuccess = true;
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $a = preg_split($re, $camelCaseName);
        fwrite(
            STDOUT,
            "\n\033[0;34m". get_class($this) . ' => ' . ucwords( implode(" ", $a )  ."\033[0m "
            )
        );
    }
    public function printSuccessInfo($success = false)
    {
        if($success) {
            fwrite(
                STDOUT,
                "\033[0;32mSuccess\033[0m"
            );
        } else {
            fwrite(
                STDOUT,
                "\033[0;31mFail\033[0m "
            );
        }

    }
}
