<?php

namespace Koralop\HCF\entity\types\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Enderman
 * @package Koralop\Extensions\entitys\types\mob
 */
class Enderman extends Monster
{
    /** @var int */
    public const NETWORK_ID = self::ENDERMAN;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Enderman';
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        return [
            Item::get(Item::ENDER_PEARL, 0, mt_rand(1, 3)),
        ];
    }
}