<?php

namespace App\Entity;


/**
 * Class Action
 * @package App\Entity
 *
 * @ORM\Entity()
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class Action
{
    const PLAYER_UNIT = 'X';
    const BOT_UNIT = 'O';
    const EMPTY_UNIT = '';

    /**
     * @var integer|null
     */
    private $coordinateY;

    /**
     * @var integer|null
     */
    private $coordinateX;

    /**
     * @var string
     */
    private $unit;

    /**
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return Action
     */
    public function setUnit(string $unit): Action
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCoordinateX(): ?int
    {
        return $this->coordinateX;
    }

    /**
     * @param int|null $coordinateX
     * @return Action
     */
    public function setCoordinateX(?int $coordinateX): Action
    {
        $this->coordinateX = $coordinateX;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCoordinateY(): ?int
    {
        return $this->coordinateY;
    }

    /**
     * @param int|null $coordinateY
     * @return Action
     */
    public function setCoordinateY(?int $coordinateY): Action
    {
        $this->coordinateY = $coordinateY;

        return $this;
    }
}