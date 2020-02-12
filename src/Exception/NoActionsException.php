<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class NoActionsException
 * @package App\Exception
 */
class NoActionsException extends \Exception
{
    public function __construct()
    {
        parent::__construct("No actions on table", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}