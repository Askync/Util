<?php
namespace Askync\Utils\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

/**
 * @method static ERROR_RESOURCE_NOT_FOUND($e404)
 * @method static ERROR_CANNOT_CONTINUE($e400)
 * @method static ERROR_FORBIDDEN_ACTION($e403)
 * @method static ERROR_UNAUTHORIZED($e401)
 * @method static ERROR_UNPROCESSABLE_ENTITY($e422)
 * @method static ERROR_OK_SUCCESS($e0)
 * @method static ERROR_MAINTENANCE_MODE($e906)
 * @class ERR_ERR
 * @method static \Askync\Utils\Facades\AskyncResponse success(array $data, string $description)
 * @method static \Askync\Utils\Facades\AskyncResponse fail(int $code, string $description, array $errors, array $extra, $debug)
 * @method static \Askync\Utils\Facades\AskyncResponse setStyle(int $responseStyle)
 *
 * @see \Askync\Utils\Utils\Response
 */

class AskyncResponse extends Facade {

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

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return AskyncResponse::class; }
}
