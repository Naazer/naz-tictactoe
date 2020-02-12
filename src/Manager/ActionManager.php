<?php


namespace App\Manager;

use App\Entity\Action;
use App\Utils\WinnerActions;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ActionManager
 * @package App\Manager
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class ActionManager
{
    /**
     * @param array $state
     *
     * @return ArrayCollection
     */
    public function makeActions(array $state): ArrayCollection
    {
        $actions = new ArrayCollection();
        foreach ($state as $x => $line) {
            array_walk($line, function (string &$unit, $y) use (&$actions, $x) {
                $actions->add($this->makeAction($y, $x, $unit));
            });
        }

        return $actions;
    }

    /**
     * @param int $y
     * @param int $x
     * @param string|null $unit
     *
     * @return Action
     */
    public function makeAction(int $y, int $x, ?string $unit = null): Action
    {
        $action = new Action();
        $action->setCoordinateY($y);
        $action->setCoordinateX($x);
        empty($unit) || $action->setUnit($unit);

        return $action;
    }

    /**
     * @return ArrayCollection
     */
    protected function getWinnerVariants(): ArrayCollection
    {
        $winnerActions = WinnerActions::get();
        $winnerCombinations = new ArrayCollection();

        array_walk($winnerActions, function ($combination) use (&$winnerCombinations) {
            $combinationCollection = new ArrayCollection();
            foreach ($combination as $action) {
                $combinationCollection->add($this->makeAction(...$action));
            }
            $winnerCombinations->add($combinationCollection);
        });

        return $winnerCombinations;
    }

    /**
     * @param ArrayCollection $availableTableActions
     *
     * @return ArrayCollection
     */
    public function getFilteredWinnerVariants(ArrayCollection $availableTableActions): ArrayCollection
    {
        $winnerCombinations = $this->getWinnerVariants();

        return $winnerCombinations->filter(
            function (ArrayCollection $combination) use ($availableTableActions, &$winnerCombinations)
            {
                $isAvailableCombination = $combination->exists(function (int $combinationKey, Action $combinationAction) use ($availableTableActions) {
                    return !in_array($combinationAction, $availableTableActions->toArray());
                });

                if ($isAvailableCombination) {
                    return $combination;
                }
            }
        );
    }
}