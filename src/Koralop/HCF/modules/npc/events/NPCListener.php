<?php

namespace Koralop\HCF\modules\npc\events;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\npc\entity\NPCEntity;
use Koralop\HCF\modules\npc\NPCManager;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Server;

class NPCListener implements Listener
{

    /** @var NPCManager */
    protected NPCManager $manager;

    /**
     * NPCListener constructor.
     * @param NPCManager $manager
     */
    public function __construct(NPCManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function EntityDamageByEntityEvent(EntityDamageByEntityEvent $event): void
    {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if ($damager instanceof HCFPlayer and $entity instanceof NPCEntity) {
            switch ($entity->getType()) {
                case 'Block Shop':
                    break;
                case 'Team':
                    Server::getInstance()->dispatchCommand($damager, 'f top');
                    break;
                case 'Kills':
                    Server::getInstance()->dispatchCommand($damager, 'leaderboards kills');
                    break;
                case 'KD':
                    Server::getInstance()->dispatchCommand($damager, 'leaderboards kd');
                    break;
                case 'Partner Crates':
                    break;
            }
            $event->setCancelled(true);
        }
    }
}