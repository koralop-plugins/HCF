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
class Repair extends Enchant
{

    /**
     * Speed constructor.
     */
    public function __construct()
    {
        parent::__construct(41, 'Repair', self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return string
     */
    public function getCustomName(): string
    {
        return 'Repair';
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [

        ];
    }

    /**
     * @param HCFPlayer $player
     */
    public function onActivate(HCFPlayer $player): void
    {
        foreach ($player->getArmorInventory()->getContents() as $slot => $item) {
            if ($item->getDamage() > 0)
                $player->getArmorInventory()->setItem($slot, $item->setDamage($item->getDamage() + 1));
        }
    }
}