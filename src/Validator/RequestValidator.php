<?php

namespace App\Validator;

use App\Entity\Action;
use App\Exception\BadRequestException;

/**
 * Class RequestValidator
 * @package App\Validator
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class RequestValidator
{
    /**
     * @param string $content
     *
     * @return bool
     * @throws BadRequestException
     */
    public function isValid(?string $content): bool
    {
        $body = json_decode($content, true);

        if (empty($content)) {
            throw new BadRequestException("Bad request. Empty body.");
        }

        if (!array_key_exists('state', $body)) {
            throw new BadRequestException("You must provide the state.");
        }

        $state = $body['state'];

        if (empty($state) || count($state) !== 3) {
            throw new BadRequestException("The table is invalid (valid table has 3 lines)");
        }

        foreach ($state as $lineValues) {
            if (count($lineValues) !== 3) {
                throw new BadRequestException("The table is invalid (A valid table line has 3 actions)");
            }

            $invalidValues = array_filter($lineValues, function($act) {
                return ($act != Action::BOT_UNIT && $act != Action::PLAYER_UNIT && !empty($act));
            });

            if ($invalidValues) {
                throw new BadRequestException("The table must contain only 'X', 'O' or empty actions");
            }
        }

        return true;
    }
}