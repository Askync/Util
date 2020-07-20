<?php

namespace Askync\Utils\Utils;

class Response
{

    const ERROR_RESOURCE_NOT_FOUND = 404;
    const ERROR_CANNOT_CONTINUE = 400;
    const ERROR_FORBIDDEN_ACTION = 403;
    const ERROR_UNAUTHORIZED = 401;
    const ERROR_UNPROCESSABLE_ENTITY = 422;
    const ERROR_OK_SUCCESS = 0;
    const ERROR_MAINTENANCE_MODE = 906;

    const RESPONSE_STYLE_ERROR_CODE = 1;
    const RESPONSE_STYLE_SUCCESS_STATE = 2;
    const RESPONSE_STYLE_SUCCES_ONLY_DATA = 3;

    protected $style = self::RESPONSE_STYLE_ERROR_CODE;

    public function setStyle($style)
    {
        if( in_array($style, [self::RESPONSE_STYLE_ERROR_CODE, self::RESPONSE_STYLE_SUCCES_ONLY_DATA, self::RESPONSE_STYLE_SUCCESS_STATE]) )
        {
            $this->style = $style;
        }
        return $this;
    }

    function buildSuccess($data, $description='Ok')
    {
        if( $data instanceof ResourceWrapper) {
            $data = $data->collection;
        }

        switch ( $this->style ) {
            case self::RESPONSE_STYLE_SUCCESS_STATE :
                return [
                    'success' => true,
                    'description' => $description,
                    'data' => $data
                ];
            case self::RESPONSE_STYLE_SUCCES_ONLY_DATA :
                return $data;
            case self::RESPONSE_STYLE_ERROR_CODE :
            default :
                return [
                    'error' => self::ERROR_OK_SUCCESS,
                    'description' => $description,
                    'data' => $data
                ];
        }
    }

    function buildFail($errorCode, $errors, $description='Process halted', $extra=null, $debug=false)
    {
        switch ( $this->style ) {
            case self::RESPONSE_STYLE_SUCCESS_STATE :
                $build = [
                    'success' => false,
                    'description' => $description,
                    'code' => $errorCode,
                    'errors' => $errors,
                ];
                $debug && $build['debug'] = $debug;
                return $build;
            case self::RESPONSE_STYLE_SUCCES_ONLY_DATA :
                $build = [
                    'errors' => $errors
                ];
                $debug && $build['debug'] = $debug;
                return $build;
            case self::RESPONSE_STYLE_ERROR_CODE :
            default :
            $build = [
                'error' => $errorCode,
                'description' => $description,
                'errors' => $errors
            ];
            $debug && $build['debug'] = $debug;
            return $build;
        }
    }

    public function fail($code = self::ERROR_CANNOT_CONTINUE, $description='Unable to continue process', $errors=[], $extra=null, $debug=null)
    {
        return response()->json( $this->buildFail($code, $errors, $description, $extra, $debug) );
    }
    public function success($data, $description='Ok')
    {
        if( $data instanceof ResourceWrapper) {
            return $this->buildSuccess($data, $description);
        }
        return response()->json( $this->buildSuccess($data, $description) );
    }

}
