<?php

namespace Koralop\HCF\modules\kit\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

/**
 * Class Mage
 * @package Koralop\HCF\modules\kit\types
 */
class Mage
{

    /**
     * @param HCFPlayer $player
     */
    public function check(HCFPlayer $player)
    {
        if ($player->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $player->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $player->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $player->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS) {

            foreach ($this->getEffects() as $effect) {
                $player->addEffect($effect);
            }

            $player->setClass(KitIds::MAGE);
        }
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [
            new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 30, 1),
            new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 30, 1),
            new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 30, 0)
        ];
    }

    /**
     * @param Item $item
     * @return EffectInstance|null
     */
    public function getEffectByItem(Item $item): ?EffectInstance
    {
        switch ($item->getId()) {
            case ItemIds::SPIDER_EYE:
                return new EffectInstance(Effect::getEffect(Effect::WITHER), 20 * 20, 2);
            case ItemIds::COAL:
                return new EffectInstance(Effect::getEffect(Effect::WEAKNESS), 20 * 20, 2);
            case ItemIds::ROTTEN_FLESH:
                return new EffectInstance(Effect::getEffect(Effect::HUNGER), 20 * 20, 4);
            case ItemIds::DYE:
                return new EffectInstance(Effect::getEffect(Effect::POISON), 20 * 20, 1);
            case ItemIds::SEEDS:
                return new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 20 * 20, 4);
            case ItemIds::GOLD_NUGGET:
                return new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 20, 2);
        }
        return null;
    }

    /**
     * @param int|null $itemId
     * @return int
     */
    public function getEnergyByItem(int $itemId = null): ?int
    {
        switch ($itemId) {
            case ItemIds::SPIDER_EYE:
                return 45;
            case ItemIds::COAL:
                return 30;
            case ItemIds::ROTTEN_FLESH:
                return 25;
            case ItemIds::DYE:
                return 20;
            case ItemIds::SEEDS:
                return 30;
            case ItemIds::GOLD_NUGGET:
                return 40;
        }
        return null;
    }
}