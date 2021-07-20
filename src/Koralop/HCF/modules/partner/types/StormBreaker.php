<?php

namespace Koralop\HCF\modules\partner\types;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\partner\lPartner;
use pocketmine\block\Air;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\scheduler\ClosureTask;

/**
 * Class StormBreaker
 * @package Koralop\HCF\modules\partner\types
 */
class StormBreaker extends lPartner
{

    /**
     * StormBreaker constructor.
     */
    public function __construct()
    {
        parent::__construct(self::GOLDEN_AXE, 0, 'StormBreaker');
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function onDamageEntity(EntityDamageByEntityEvent $event): void
    {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if ($damager instanceof HCFPlayer && $entity instanceof HCFPlayer) {
            $helmet = $entity->getArmorInventory()->getHelmet();

            if ($helmet instanceof Air)
                return;

            $entity->getArmorInventory()->setHelmet(self::get(self::AIR, 0, 1));

            HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($entity, $helmet) {
                if ($entity == null || $helmet == null)
                    return;

                $entity->getArmorInventory()->setHelmet($helmet);
            }), 20 * 10);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event): void
    {
        // TODO: Implement onInteract() method.
    }
}