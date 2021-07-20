<?php

namespace Koralop\HCF\modules\koth;

use Koralop\HCF\HCFPlayer;
use pocketmine\level\Position;

/**
 * Class Koth
 * @package Koralop\HCF\modules\koth
 */
class Koth
{

    /** @var int|null */
    protected ?int $time = null;

    /** @var string */
    protected string $name;

    /** @var Position */
    protected Position $pos1;

    /** @var Position */
    protected Position $pos2;

    /** @var HCFPlayer|null */
    protected ?HCFPlayer $player = null;

    /**
     * Koth constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->name = $config['name'];

        $this->pos1 = $config['pos1'];
        $this->pos2 = $config['pos2'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int|null $time
     */
    public function setTime(?int $time)
    {
        $this->time = $time;
    }

    /**
     * @return int|null
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * @param HCFPlayer|null $player
     */
    public function setCapturer(?HCFPlayer $player)
    {
        $this->player = $player;
    }

    /**
     * @return HCFPlayer|null
     */
    public function getCapturer(): ?HCFPlayer
    {
        return $this->player;
    }

    /**
     * @return Position
     */
    public function getPosition1(): Position
    {
        return $this->pos1;
    }

    /**
     * @return Position
     */
    public function getPosition2(): Position
    {
        return $this->pos2;
    }

    /**
     * @param Position $position
     * @return bool
     */
    public function isInPosition(Position $position): bool
    {
        $x = $position->getFloorX();
        $y = $position->getFloorY();
        $z = $position->getFloorZ();

        $xMin = min($this->getPosition1()->getFloorX(), $this->getPosition2()->getFloorX());
        $xMax = max($this->getPosition1()->getFloorX(), $this->getPosition2()->getFloorX());

        $yMin = min($this->getPosition1()->getFloorY(), $this->getPosition2()->getFloorY());
        $yMax = max($this->getPosition1()->getFloorY(), $this->getPosition2()->getFloorY());

        $zMin = min($this->getPosition1()->getFloorZ(), $this->getPosition2()->getFloorZ());
        $zMax = max($this->getPosition1()->getFloorZ(), $this->getPosition2()->getFloorZ());

        return $x >= $xMin && $x <= $xMax && $y >= $yMin && $y <= $yMax && $z >= $zMin && $z <= $zMax;
    }
}