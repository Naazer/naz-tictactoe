<?php

namespace App\Service;

use App\Entity\Table;
use App\Entity\Action;
use App\Manager\StateManager;
use App\Manager\ActionManager;
use App\Exception\NoActionsException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GameService
 * @package App\Service
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class GameService
{
    /**
     * @var StateManager
     */
    private $stateManager;

    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var Table
     */
    private $table;

    /**
     * GameService constructor.
     *
     * @param StateManager $stateManager
     * @param ActionManager $actionManager
     */
    public function __construct(
        StateManager $stateManager,
        ActionManager $actionManager
    ) {
        $this->stateManager = $stateManager;
        $this->actionManager = $actionManager;
    }

    /**
     * @param array $state
     * @return array
     * @throws \Exception
     */
    public function action(array $state): array
    {
        $actions = $this->actionManager->makeActions($state);
        $this->table = $this->stateManager->makeTable($actions);
        $playerVariants = $this->getUnitVariants(Action::PLAYER_UNIT);
        $botVariants = $this->getUnitVariants(Action::BOT_UNIT);
        $nextAction = $this->getNextAction($playerVariants, $botVariants);

        return ($nextAction instanceof Action) ? array_values((array) $nextAction) : [];
    }

    /**
     * @param ArrayCollection $playerVariants
     * @param ArrayCollection $botVariants
     *
     * @return Action|null
     */
    protected function getNextAction(ArrayCollection $playerVariants, ArrayCollection $botVariants): ?Action
    {
        try {
            $nextAction = $this->predict($playerVariants, $botVariants);
            isset($nextAction) || $nextAction = $this->getInitAction();
            if ($nextAction) {
                $this->table->getActions()->forAll(function (int $k, Action $action) use (&$nextAction) {
                    if ($action->getCoordinateX() == $nextAction->getCoordinateX() && $action->getCoordinateY() == $nextAction->getCoordinateY()) {
                        $action->setUnit(Action::BOT_UNIT);
                        $nextAction = $action;
                    }
                    return true;
                });
            }
        } catch (NoActionsException $exception) {
            $nextAction = null;
        }

        return $nextAction;
    }

    /**
     * @param ArrayCollection $playerVariants
     * @param ArrayCollection $botVariants
     *
     * @return Action|null
     *
     * @throws NoActionsException
     */
    protected function predict(ArrayCollection $playerVariants, ArrayCollection $botVariants)
    {
        $nextPlayerActions = $this->getNextActionsByUnit($playerVariants);
        $willPlayerWin = (bool) ($nextPlayerActions->first() && $nextPlayerActions->first()->count() == 1);
        $nextBotActions = $this->getNextActionsByUnit($botVariants);
        $willBotWin = (bool) ($nextBotActions->first() && $nextBotActions->first()->count() == 1);
        $willBotWin && $nextAction = $this->predictAction($nextBotActions);
        $willPlayerWin && !$willBotWin && $nextAction = $this->predictAction($nextBotActions);

        return isset($nextAction) ? $nextAction : null;
    }

    /**
     * @param ArrayCollection $actions
     *
     * @return ArrayCollection
     *
     * @throws NoActionsException
     */
    protected function getNextActionsByUnit(ArrayCollection $actions) : ArrayCollection
    {
        $availableActions = $this->stateManager->getEmptyActions($this->table);
        $possibleVariants = $this->actionManager->getFilteredWinnerVariants($availableActions);
        if ($availableActions->isEmpty()) {
            throw new NoActionsException();
        }
        $variants = $possibleVariants->filter(
            function (ArrayCollection $variant) use ($actions, $availableActions) {
                foreach ($actions as $act) {
                    $match = true;
                    for ($i = 0; $i <= $variant->count(); $i++) {
                        if (is_null($act->get($i))) {
                            continue;
                        }
                        if ($act->get($i) != $variant->get($i)) {
                            $match = false;
                        }
                    }
                    if ($match) {
                        $variant->exists(function (int $vK, Action $vAct) use (&$variant, $availableActions) {
                            if (!in_array($vAct, $availableActions->toArray())) {
                                $variant->remove($vK);
                            }
                        });
                        if (!$variant->isEmpty()) {
                            return $variant;
                        }
                    }
                }
            }
        );

        return $this->sortVariants($variants);
    }

    /**
     * @param ArrayCollection $actions
     *
     * @return Action|null
     */
    protected function predictAction(ArrayCollection $actions): ?Action
    {
        if (!$actions->isEmpty()) {
            $predictAction = min($actions->toArray());
            if ($predictAction) {
                return $predictAction->first();
            }
        }

        return null;
    }

    /**
     * @return Action|null
     */
    protected function getInitAction(): ?Action
    {
        $availableActions = $this->stateManager->getEmptyActions($this->table);
        $initAction = $availableActions->filter(function (Action $act) {
            if ($act->getCoordinateY() === 1 && $act->getCoordinateX() === 1 && empty($act->getUnit())) {
                return $act;
            }
        });

        if (!$initAction->first()) {
            return $availableActions->first();
        }

        return $initAction->first() ?: null;
    }

    /**
     * @param string $unit
     *
     * @return ArrayCollection
     *
     * @throws \Exception
     */
    protected function getUnitVariants(string $unit): ArrayCollection
    {
        $availableActions = $this->stateManager->getEmptyActions($this->table);
        $possibleVariants = $this->actionManager->getFilteredWinnerVariants($availableActions);
        $possibleVariants->exists(
            function (int $vKey, ArrayCollection $variant) use ($unit, &$possibleVariants, $availableActions) {
                foreach ($variant as $v) {
                    if (!in_array($v, $availableActions->toArray())) {

                        if (!$this->unitHas($v, $unit)) {
                            $possibleVariants->remove($vKey);
                        }
                    }
                }
            }
         );

        return $this->sortVariants($possibleVariants);
    }

    /**
     * @param Action $variantAction
     * @param string $unit
     *
     * @return bool
     */
    protected function unitHas(Action $variantAction, string $unit)
    {
        $gActions = $this->stateManager->getGroupedActions($this->table, $unit);
        return $gActions->exists(function ($k, Action $action) use ($variantAction) {
            if ($action->getCoordinateY() === $variantAction->getCoordinateY() && $action->getCoordinateX() === $variantAction->getCoordinateX()) {
                return true;
            }
            return false;
        });
    }

    /**
     * @param ArrayCollection $variants
     *
     * @return ArrayCollection
     *
     * @throws \Exception
     */
    protected function sortVariants(ArrayCollection $variants): ArrayCollection
    {
        $iterator = $variants->getIterator();
        $iterator->uasort(function (ArrayCollection $first, ArrayCollection $second) {
            if ($first->count() == $second->count()) {
                return 0;
            }
            return ($first->count() < $second->count()) ? -1 : 1;
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }
}