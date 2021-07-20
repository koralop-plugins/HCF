<?php

namespace Koralop\HCF\inventory\types;

use Koralop\HCF\tile\types\Dropper;
use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\WindowTypes;

/**
 * Class DropperInventory
 * @package Koralop\HCF\inventory\types
 */
class DropperInventory extends ContainerInventory
{
    /**
     * DropperInventory constructor.
     * @param Dropper $tile
     */
    public function __construct(Dropper $tile)
    {
        parent::__construct($tile);
    }

    /**
     * @return Vector3
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @return int
     */
    public function getDefaultSize(): int
    {
        return 9;
    }

    /**
     * @return int
     */
    public function getNetworkType(): int
    {
        return WindowTypes::DROPPER;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Dropper';
    }
}