<?php

namespace Koralop\HCF\entity\types\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Creeper
 * @package Koralop\Extensions\entitys\types\mob
 */
class Creeper extends Monster
{
    /** @var int */
    public const NETWORK_ID = self::CREEPER;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Creeper';
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        return [
            Item::get(Item::GUNPOWDER, 0, mt_rand(0, 2))
        ];
    }
}