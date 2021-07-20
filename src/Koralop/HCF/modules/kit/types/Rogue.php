<?php

namespace Koralop\HCF\modules\kit\types;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\KitIds;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\ItemIds;

/**
 * Class Rogue
 * @package Koralop\HCF\modules\kit\types
 */
class Rogue
{

    /**
     * @param HCFPlayer $player
     */
    public function check(HCFPlayer $player)
    {
      if($player->getArmorInventory()->getHelmet()->getId() === ItemIds::CHAINMAIL_HELMET && $player->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $player->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $player->getArmorInventory()->getBoots()->getId() === ItemIds::CHAINMAIL_BOOTS) {

          foreach ($this->getEffects() as $effect) {
              $player->addEffect($effect);
          }

          $player->setClass(KitIds::ROGUE);
      }
    }

    /**
     * @return EffectInstance[]
     */
    public function getEffects(): array
    {
        return [
            new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 30, 2),
            new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 30, 0),
            new EffectInstance(Effect::getEffect(Effect::JUMP), 20 * 30, 1)
        ];
    }
}