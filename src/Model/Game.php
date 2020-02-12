<?php

namespace App\Model;

use App\Entity\Action;
use App\Utils\WinnerActions;

/**
 * Class Game
 * @package App\Model
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class Game
{
    /**
     * @var bool
     */
    private $standoff = false;

    /**
     * @var array
     */
    private $winner = [];

    /**
     * Game constructor.
     *
     * @param $state
     * @param $nextAction
     */
    public function __construct($state, $nextAction)
    {
        $stateCoordinates = $this->createStateWithCoords($state, $nextAction);
        $playerActions = $this->filterCoordinatesByUnit($stateCoordinates, Action::PLAYER_UNIT);
        $winnerActions = $this->getWinnerActions($playerActions);

        if ($winnerActions) {
            $this->winner = $this->createWinner($winnerActions, Action::PLAYER_UNIT);
        }

        if (is_null($winnerActions)) {
            $botActions = $this->filterCoordinatesByUnit($stateCoordinates, Action::BOT_UNIT);
            $winnerActions = $this->getWinnerActions($botActions);
            if ($winnerActions) {
                $this->winner = $this->createWinner($winnerActions, Action::BOT_UNIT);
            }
        }

        if (is_null($winnerActions)) {
            $availableActions = $this->filterCoordinatesByUnit($stateCoordinates, "");
            if (empty($availableActions)) {
                $this->standoff = true;
            }
        }
    }

    /**
     * @return bool
     */
    public function isStandoff(): bool
    {
        return $this->standoff;
    }

    /**
     * @return array
     */
    public function getWinner(): array
    {
        return $this->winner;
    }

    /**
     * @param array $state
     * @param array $nextAction
     *
     * @return array
     */
    private function createStateWithCoords(array $state, array $nextAction)
    {
        $stateCoordinates = [];
        foreach ($state as $x => $lineState) {
            array_walk($lineState, function (string &$unit, $y) use (&$stateCoordinates, $x, $nextAction) {
                if (!empty($nextAction) && $nextAction[0] == $y && $nextAction[1] == $x && $nextAction[2]) {
                    $stateCoordinates[] = $nextAction;
                } else {
                    $stateCoordinates[] = [$y, $x, $unit];
                }
            });
        }
        return $stateCoordinates;
    }


    /**
     * @param array $winnerActions
     * @param $playerUnit
     *
     * @return array
     */
    private function createWinner(array $winnerActions, $playerUnit) : array
    {
        return [
            'unit' => $playerUnit,
            'actions' => $winnerActions,
        ];
    }

    /**
     * @param $playerActions
     *
     * @return mixed
     */
    private function getWinnerActions($playerActions)
    {
        $winnerCombinations = WinnerActions::get();
        array_walk($winnerCombinations, function (array $combination) use ($playerActions, &$winnerActions) {
            $matchPoints = 0;
            foreach ($playerActions as $action) {
                array_pop($action);
                if (in_array($action, $combination)) {
                    $matchPoints++;
                }
            }

            if ($matchPoints === 3) {
                $winnerActions = $combination;
            }
        });

        return $winnerActions;
    }

    /**
     * @param $stateCoordinates
     * @param $playerUnit
     *
     * @return array
     */
    private function filterCoordinatesByUnit($stateCoordinates, $playerUnit)
    {
        $playerActions = array_map(
            function ($action) use ($playerUnit) {
                if ($action[2] == $playerUnit) {
                    return $action;
                }
            },
            $stateCoordinates
        );

        return array_filter($playerActions, function ($value) {
            return !is_null($value);
        });
    }
}
