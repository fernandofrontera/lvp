<?php


namespace App\Exception;

use Throwable;

class InvalidException extends \Exception
{

    public function __construct(
        $message = "Información inválida o insuficiente.",
        $code = 400,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}