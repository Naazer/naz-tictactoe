<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Board
 * @package App\Entity
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class Board
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
     * Board constructor.
     * @param ArrayCollection|null $actions
     */
    public function __construct(?ArrayCollection $actions = null)
    {
        is_null($actions) || $this->actions = new ArrayCollection();
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
     * @return Board
     */
    public function addAction(Action $action): Board
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
     * @return Board
     */
    public function setCompleted(bool $completed): Board
    {
        $this->completed = $completed;

        return $this;
    }
}