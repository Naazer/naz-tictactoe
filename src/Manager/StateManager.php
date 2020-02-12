<?php


namespace App\Manager;

use App\Entity\Table;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class StateManager
 * @package App\Manager
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class StateManager
{
    /**
     * @param ArrayCollection|null $actions
     *
     * @return Table
     */
    public function makeTable(ArrayCollection $actions): Table
    {
        $table = new Table($actions);
        $emptyActions = $this->getEmptyActions($table);
        $emptyActions->count() === 0 && $table->setCompleted(true);

        return $table;
    }

    /**
     * @param Table $table
     *
     * @return ArrayCollection
     */
    public function getEmptyActions(Table $table): ArrayCollection
    {
        /**
         * @var ArrayCollection
         */
        $actions = clone $table->getActions();

        if ($actions->toArray()) {
            foreach ($actions->toArray() as $action) {
                if (!empty($action->getUnit())) {
                    $actions->removeElement($action);
                }
            }
        }


        return $actions;
    }

    /**
     * @param Table $table
     * @param string $unit
     *
     * @return ArrayCollection|null
     */
    public function getGroupedActions(Table $table, string $unit): ?ArrayCollection
    {
        $actions = clone $table->getActions();
        foreach ($actions as $action) {
            if ($action->getUnit() != $unit) {
                $actions->removeElement($action);
            }
        }

        return $actions;
    }
}