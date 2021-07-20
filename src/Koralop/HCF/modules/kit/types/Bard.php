<?php

namespace Koralop\HCF\modules\kit\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;

/**
 * Class Bard
 * @package Koralop\HCF\modules\kit\types
 */
class Bard
{

    /**
     * @param HCFPlayer $player
     */
    public function check(HCFPlayer $player)
    {
        if ($player->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $player->getArmorInventory()->getChestplate()->getId() === ItemIds::GOLD_CHESTPLATE && $player->getArmorInventory()->getLeggings()->getId() === ItemIds::GOLD_LEGGINGS && $player->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS) {

            foreach ($this->getEffects() as $effect) {
                $player->addEffect($effect);
            }

            $player->setClass(KitIds::BARD);
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
            case ItemIds::DYE:
                return new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 30 * 10, 1);
            case ItemIds::MAGMA_CREAM:
                return new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 50 * 50, 1);
        }
        return null;
    }

    public function getEffectNameByItem(Item $item): ?string
    {
        switch ($item->getId()) {
            case ItemIds::BLAZE_POWDER:
                return 'Strength';
            case ItemIds::SUGAR:
                return 'Speed';
            case ItemIds::IRON_INGOT:
                return 'Resistance';
            case ItemIds::GHAST_TEAR:
                return 'Regeneration';
            case ItemIds::FEATHER:
                return 'Jump';
            case ItemIds::DYE:
                return 'Invisibility';
            case ItemIds::MAGMA_CREAM:
                return 'Fire Resistance';
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
            case ItemIds::FEATHER:
            case ItemIds::IRON_INGOT:
            case ItemIds::DYE:
            $energyCost = 30;
                break;
            case ItemIds::BLAZE_POWDER:
                $energyCost = 40;
                break;
            case ItemIds::GHAST_TEAR:
                $energyCost = 35;
                break;
            case ItemIds::MAGMA_CREAM:
                $energyCost = 25;
                break;
        }
        return $energyCost;
    }
}