<?php

namespace Koralop\HCF\item\types;

use pocketmine\block\Block;
use pocketmine\item\Item;

/**
 * Class Dropper
 * @package Koralop\HCF\item\types
 */
class Dropper extends Item
{

    /**
     * Dropper constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0)
    {
        parent::__construct(self::DROPPER, $meta, "Dropper");
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return new \Koralop\HCF\block\types\Dropper();
    }

    /**
     * @return int
     */
    public function getMaxStackSize(): int
    {
        return 1;
    }
}