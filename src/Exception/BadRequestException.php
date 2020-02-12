<?php

namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class BadRequestException
 * @package App\Exception
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class BadRequestException extends \Exception
{
    /**
     * BadRequestException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = Response::HTTP_BAD_REQUEST, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}