<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Table
 * @package App\Entity
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class Table
{
    /**
     * @var ArrayCollection
     */
    private $actions;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $completed = false;

    /**
     * Table constructor.
     * @param ArrayCollection|null $actions
     */
    public function __construct(?ArrayCollection $actions = null)
    {
        is_null($actions) || $this->actions = $actions;
    }

    /**
     * @return ArrayCollection|Action[]
     */
    public function getActions(): ArrayCollection
    {
        return $this->actions;
    }

    /**
     * @param Action $action
     * @return Table
     */
    public function addAction(Action $action): Table
    {
        $this->actions->add($action);

        return $this;
    }

    /**
     * @param Action $action
     */
    public function removeAction(Action $action)
    {
        $this->actions->removeElement($action);
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return Table
     */
    public function setCompleted(bool $completed): Table
    {
        $this->completed = $completed;

        return $this;
    }
}