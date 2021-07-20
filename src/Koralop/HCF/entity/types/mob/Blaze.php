<?php

namespace Koralop\HCF\entity\types\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Blaze
 * @package Koralop\Extensions\entitys\types\mob
 */
class Blaze extends Monster
{
    /** @var int */
    public const NETWORK_ID = self::BLAZE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Blaze';
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        return [
            Item::get(Item::BLAZE_ROD, 0, mt_rand(0, 1))
        ];
    }
}