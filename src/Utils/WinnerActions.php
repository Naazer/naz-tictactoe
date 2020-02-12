<?php

namespace App\Utils;

/**
 * Class WinnerActions
 * @package App\Utils
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class WinnerActions
{
    const CROSS_L = [
        [0, 0],
        [1, 1],
        [2, 2],
    ];

    const CROSS_R = [
        [2, 0],
        [1, 1],
        [0, 2]
    ];

    /**
     * @return array
     */
    public static function get(): array
    {
        $actions = [self::CROSS_L, self::CROSS_R];

        // vertical and horizontal lines
        for ($x = 0; $x < 3; $x++) {
            $actions[] = [
                [$x, 0],
                [$x, 1],
                [$x, 2],
            ];
            $actions[] = [
                [0, $x],
                [1, $x],
                [2, $x],
            ];
        }

        return $actions;
    }
}