<?php

namespace Koralop\HCF\modules\kit\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

/**
 * Class Archer
 * @package Koralop\HCF\modules\kit\types
 */
class Archer
{


    /**
     * @param HCFPlayer $player
     */
    public function check(HCFPlayer $player)
    {
        if ($player->getArmorInventory()->getHelmet()->getId() === ItemIds::LEATHER_HELMET && $player->getArmorInventory()->getChestplate()->getId() === ItemIds::LEATHER_CHESTPLATE && $player->getArmorInventory()->getLeggings()->getId() === ItemIds::LEATHER_LEGGINGS && $player->getArmorInventory()->getBoots()->getId() === ItemIds::LEATHER_BOOTS) {
            foreach ($this->getEffects() as $effect) {
                $player->addEffect($effect);
            }

            $player->setClass(KitIds::ARCHER);
        }
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [
            new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 30, 2),
            new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 30, 1)
        ];
    }

    /**
     * @param Item $item
     * @return EffectInstance|null
     */
    public function getEffectByItem(Item $item): ?EffectInstance
    {
        switch ($item->getId()) {
            case ItemIds::BLAZE_POWDER:
                return new EffectInstance(Effect::getEffect(Effect::STRENGTH), 20 * 10, 2);
            case ItemIds::SUGAR:
                return new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 15, 3);
            case ItemIds::IRON_INGOT:
                return new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 15, 3);
            case ItemIds::GHAST_TEAR:
                return new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 10, 2);
            case ItemIds::FEATHER:
                return new EffectInstance(Effect::getEffect(Effect::JUMP), 20 * 10, 3);
        }
        return null;
    }

    /**
     * @param int|null $itemId
     * @return int
     */
    public function getEnergyByItem(int $itemId = null): ?int
    {
        $energyCost = null;
        switch ($itemId) {
            case ItemIds::SUGAR:
                $energyCost = 20;
                break;
            case ItemIds::IRON_INGOT:
                $energyCost = 30;
                break;
            case ItemIds::BLAZE_POWDER:
                $energyCost = 40;
                break;
            case ItemIds::GHAST_TEAR:
                $energyCost = 35;
                break;
            case ItemIds::FEATHER:
                $energyCost = 30;
                break;
            case ItemIds::DYE:
                $energyCost = 30;
                break;
            case ItemIds::MAGMA_CREAM:
                $energyCost = 25;
                break;
            case ItemIds::SPIDER_EYE:
                $energyCost = 40;
                break;
        }
        return $energyCost;
    }

}