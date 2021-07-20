<?php

namespace Koralop\HCF\events;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\InvMenu\ChestInventory;
use muqsit\invmenu\inventory\InvMenuInventory;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\inventory\transaction\action\SlotChangeAction;

class InventoryListener implements Listener
{

    /**
     * @param InventoryTransactionEvent $event
     */
    public function InventoryTransactionEvent(InventoryTransactionEvent $event): void
    {
        $transaction = $event->getTransaction();
        foreach ($transaction->getActions() as $action) {
            if ($action instanceof SlotChangeAction) {
                $inventory = $action->getInventory();
                if ($inventory instanceof ChestInventory) {
                    $event->setCancelled(true);
                }
            }
        }
    }

    /**
     * @param InventoryCloseEvent $event
     */
    public function InventoryCloseEvent(InventoryCloseEvent $event): void
    {
        $player = $event->getPlayer();
        $inventory = $event->getInventory();
        if ($inventory instanceof ChestInventory) {
            if (!$player instanceof HCFPlayer)
                return;

            $inventory->removeFakeBlock($player);
        }
    }
}