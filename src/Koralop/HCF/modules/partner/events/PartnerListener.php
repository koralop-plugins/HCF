<?php

namespace Koralop\HCF\modules\partner\events;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\partner\lPartner;
use Koralop\HCF\modules\partner\PartnerManager;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

/**
 * Class PartnerListener
 * @package Koralop\HCF\modules\partner\events
 */
class PartnerListener implements Listener
{

    /** @var PartnerManager */
    protected PartnerManager $manager;

    /**
     * PartnerListener constructor.
     * @param PartnerManager $manager
     */
    public function __construct(PartnerManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function PlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item instanceof lPartner)
            $item->onInteract($event);

        if ($item->getNamedTag()->hasTag('PartnerPackage')) {

            $event->setCancelled(true);

            $this->manager->randItems($player, 4);

            $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1));
        }
    }

    /**
     * @param EntityDamageByEntityEvent $event
     */
    public function EntityDamageByEntityEvent(EntityDamageByEntityEvent $event): void
    {
        $damager = $event->getDamager();
        if ($damager instanceof HCFPlayer) {

            $item = $damager->getInventory()->getItemInHand();

            if ($item instanceof lPartner)
                $item->onDamageEntity($event);

        }
    }
}