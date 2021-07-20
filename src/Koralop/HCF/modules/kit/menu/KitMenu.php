<?php

namespace Koralop\HCF\modules\kit\menu;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\kit\Kit;
use Koralop\HCF\modules\kit\KitManager;
use Koralop\HCF\utils\Time;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

/**
 * Class KitMenu
 * @package Koralop\HCF\modules\kit\menu
 */
class KitMenu
{

    /** @var HCFPlayer */
    protected HCFPlayer $player;

    /** @var KitManager */
    protected KitManager $kitManager;

    /**
     * KitMenu constructor.
     * @param HCFPlayer $player
     * @param KitManager $kitManager
     */
    public function __construct(HCFPlayer $player, KitManager $kitManager)
    {
        $this->kitManager = $kitManager;

        $this->sendMenu($player);
    }

    /**
     * @param HCFPlayer $player
     */
    public function sendMenu(HCFPlayer $player): void
    {

        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);

        $menu->setName(TextFormat::GOLD . TextFormat::BOLD . 'Kit Selector');

        foreach ($this->kitManager->getAllKits() as $kitName => $kit) {
            if ($kit instanceof Kit) {
                if ($kitName != 'Deathban') {
                    $args = explode(':', $kit->getItem());

                    $item = Item::get($args[0], $args[1]);
                    $item->setCustomName($kit->getFormat());
                    $item->setLore([
                        TextFormat::GOLD . 'Cooldown: ' . TextFormat::WHITE . '24 Hours',
                        TextFormat::GOLD . 'Available in: ' . TextFormat::WHITE . Time::kitTime(($player->getCooldowns()->getKitCooldown($kitName) == null ? 0 : $player->getCooldowns()->getKitCooldown($kitName)))
                    ]);

                    $menu->getInventory()->setItem($kit->getSlot(), $item);
                }
            }
        }

        $menu->send($player);

        $menu->setListener(function (HCFPlayer $player, Item $in, Item $out, SlotChangeAction $action) {
            foreach ($this->kitManager->getAllKits() as $kitName => $kit) {
                if ($kit instanceof Kit) {
                    if ($player->getCooldowns()->getKitCooldown($kitName) == null or $player->isGod()) {
                        if ($kit->getFormat() == $in->getCustomName()) {
                            $kit->setKit($player);
                            return;
                        }
                    } else
                        $player->sendMessage(KitManager::PREFIX . TextFormat::RED . 'You still have ' . Time::kitTime(($player->getCooldowns()->getKitCooldown($kitName) == null ? 0 : $player->getCooldowns()->getKitCooldown($kitName))) . ' cooldown left on ' . $kit->getFormat() . TextFormat::RESET . TextFormat::RED . '.');
                }
            }
        });
    }
}