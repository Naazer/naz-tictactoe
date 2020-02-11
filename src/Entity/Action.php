<?php

namespace App\Entity;

/**
 * Class Action
 * @package App\Entity
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class Action
{
    const DEFAULT_PLAYER_UNIT = 1;

    /**
     * The unit could be 1 (represent X) and 0
     * @var integer
     */
    private $unit;

    /**
     * @var integer
     */
    private $coordinateX;

    /**
     * @var integer
     */
    private $coordinateY;

    /**
     * Action constructor.
     * @param int $coordinateX
     * @param int $coordinateY
     * @param string|null $unit
     */
    public function __construct(int $coordinateX, int $coordinateY, ?string $unit = self::DEFAULT_PLAYER_UNIT)
    {
        $this->unit = $unit;
        $this->coordinateX = $coordinateX;
        $this->coordinateY = $coordinateY;
    }

    /**
     * @return int
     */
    public function getUnit(): int
    {
        return $this->unit;
    }

    /**
     * @param int $unit
     */
    public function setUnit(int $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getCoordinateX(): int
    {
        return $this->coordinateX;
    }

    /**
     * @param int $coordinateX
     */
    public function setCoordinateX(int $coordinateX): void
    {
        $this->coordinateX = $coordinateX;
    }

    /**
     * @return int
     */
    public function getCoordinateY(): int
    {
        return $this->coordinateY;
    }

    /**
     * @param int $coordinateY
     */
    public function setCoordinateY(int $coordinateY): void
    {
        $this->coordinateY = $coordinateY;
    }
}