<?php

declare(strict_types=1);

namespace Viniciuscoutinh0\Minimal\Enums;

enum HttpStatus: int
{
    case Ok = 200;
    case Created = 201;
    case NoContent = 204;

    case MovedPermanently = 301;
    case Found = 302;

    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case Conflict = 409;
    case UnprocessableEntity = 422;

    case InternalServerError = 500;
    case NotImplemented = 501;
    case ServiceUnavailable = 503;
}
