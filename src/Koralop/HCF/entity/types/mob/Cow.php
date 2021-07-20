<?php

namespace Koralop\HCF\entity\types\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Cow
 * @package Koralop\Extensions\entitys\types\mob
 */
class Cow extends Monster
{
    /** @var int */
    public const NETWORK_ID = self::COW;

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Cow';
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        return [
            Item::get(Item::STEAK, 0, mt_rand(1, 3)),
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
        ];
    }
}