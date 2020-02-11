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
    const DEFAULT_DIMENSION = 3;
    const DEFAULT_MATRIX_VAL = null;

    /**
     * @var ArrayCollection
     */
    private $actions;

    /**
     * @var int
     */
    private $dimension;

    /**
     * @var array
     */
    private $matrix;

    /**
     * @var bool
     */
    private $completed = false;

    /**
     * Board constructor.
     *
     * @param int|null $dimension
     */
    public function __construct(?int $dimension)
    {
        $this->prepareDimension($dimension);
        $this->clearMatrix();
    }

    /**
     * @return ArrayCollection
     */
    public function getActions(): ArrayCollection
    {
        return $this->actions;
    }

    /**
     * @param ArrayCollection $actions
     */
    public function setActions(ArrayCollection $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @param Action $action
     */
    public function addAction(Action $action)
    {
        $this->actions->add($action);
    }

    /**
     * @return int
     */
    public function getDimension(): int
    {
        return $this->dimension;
    }

    /**
     * @param int $dimension
     */
    public function setDimension(int $dimension): void
    {
        $this->dimension = $dimension;
    }

    /**
     * @return array
     */
    public function getMatrix(): array
    {
        return $this->matrix;
    }

    /**
     * @param array $matrix
     */
    public function setMatrix(array $matrix): void
    {
        $this->matrix = $matrix;
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
     */
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    /**
     * @param int|null $dimension
     */
    private function prepareDimension(?int $dimension)
    {
        $this->dimension = $dimension ?? self::DEFAULT_DIMENSION;
    }

    /**
     * Fill matrix with empty fields
     */
    private function clearMatrix()
    {
        $defaultValues = array_fill(0, $this->getDimension(), self::DEFAULT_MATRIX_VAL);
        $this->matrix = array_fill(0, $this->getDimension(), $defaultValues);
    }
}