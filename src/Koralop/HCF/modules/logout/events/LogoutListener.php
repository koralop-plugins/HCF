<?php

namespace Koralop\HCF\modules\logout\events;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\logout\entity\LogoutEntity;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\math\Vector3;

/**
 * Class LogoutListener
 * @package Koralop\HCF\modules\logout
 */
class LogoutListener implements Listener
{

    /**
     * @param PlayerQuitEvent $event
     */
    public function PlayerQuitEvent(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {

            if ($player->getCooldowns()->getCombatTag() != null) {
                $nbt = Entity::createBaseNBT(new Vector3($player->getX(), $player->getY(), $player->getZ()));
                $entity = new LogoutEntity($player->getLevel(), $nbt);

                $entity->setPlayer($player);

                $player->getLevel()->addEntity($entity);
                $entity->spawnToAll();
            }
        }
    }
}