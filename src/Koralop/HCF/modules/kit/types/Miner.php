<?php

namespace Koralop\HCF\modules\kit\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\ItemIds;

/**
 * Class Miner
 * @package Koralop\HCF\modules\kit\types
 */
class Miner
{

    /**
     * @param HCFPlayer $player
     */
    public function check(HCFPlayer $player)
    {
        if ($player->getArmorInventory()->getHelmet()->getId() === ItemIds::IRON_HELMET && $player->getArmorInventory()->getChestplate()->getId() === ItemIds::IRON_CHESTPLATE && $player->getArmorInventory()->getLeggings()->getId() === ItemIds::IRON_LEGGINGS && $player->getArmorInventory()->getBoots()->getId() === ItemIds::IRON_BOOTS) {

            $player->setClass(KitIds::MINER);

            foreach ($this->getEffects() as $effect) {
                $player->addEffect($effect);
            }
        }
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [
            new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 20 * 20, 0),
            new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 20 * 20, 0),
            new EffectInstance(Effect::getEffect(Effect::HASTE), 20 * 20, 1),
        ];
    }
}