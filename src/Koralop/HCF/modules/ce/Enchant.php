<?php

namespace Koralop\HCF\modules\ce;

use Koralop\HCF\HCFPlayer;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;

/**
 * Class Enchant
 * @package Koralop\HCF\modules\ce
 */
abstract class Enchant extends Enchantment
{

    /**
     * @return string
     */
    abstract public function getCustomName(): string;

    /**
     * @return EffectInstance[]
     */
    abstract public function getEffects(): array;

    /**
     * @param HCFPlayer $player
     */
    abstract public function onActivate(HCFPlayer $player): void;
}