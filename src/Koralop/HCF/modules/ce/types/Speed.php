<?php

namespace Koralop\HCF\modules\ce\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ce\Enchant;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

/**
 * Class Speed
 * @package Koralop\HCF\modules\ce\types
 */
class Speed extends Enchant
{

    /**
     * Speed constructor.
     */
    public function __construct()
    {
        parent::__construct(40, 'Speed II', self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return string
     */
    public function getCustomName(): string
    {
        return 'Speed II';
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [
            new EffectInstance(Effect::getEffect(Effect::SPEED), 60, 1)
        ];
    }

    /**
     * @param HCFPlayer $player
     */
    public function onActivate(HCFPlayer $player): void
    {
        foreach ($this->getEffects() as $effect)
            $player->addEffect($effect);
    }
}