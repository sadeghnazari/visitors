<?php

namespace Blanfordia\Visitors\Data\Repositories;

class Error extends Repository
{
    public function getMessageFromException($exception) {
        if ($message = $exception->getMessage()) {
            return $message;
        }

        return $message;
    }

    public function getCodeFromException($exception) {
        if (method_exists($exception, 'getCode') && $code = $exception->getCode()) {
            return $code;
        }

        if (method_exists($exception, 'getStatusCode') && $code = $exception->getStatusCode()) {
            return $code;
        }

        return null;
    }
}
