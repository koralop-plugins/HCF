<?php

namespace Koralop\HCF\modules\shop\events;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\modules\shop\ShopManager;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\scheduler\ClosureTask;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

/**
 * Class ShopListener
 * @package Koralop\HCF\modules\shop\events
 */
class ShopListener implements Listener
{

    /** @var ShopManager */
    protected ShopManager $manager;

    /**
     * ShopListener constructor.
     * @param ShopManager $manager
     */
    public function __construct(ShopManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param SignChangeEvent $event
     */
    public function SignChangeEvent(SignChangeEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player instanceof HCFPlayer) {
            if ($player->isOp()) {

                $block = $event->getBlock();

                if ($event->getLine(0) == "" or $event->getLine(1) == "" or $event->getLine(2) == "")
                    return;

                $item = Item::get(explode(':', $event->getLine(1))[0], (isset(explode(':', $event->getLine(1))[1]) ? explode(':', $event->getLine(1))[1] : 0), 1);
                if (strtolower($event->getLine(0)) === "buy") {

                    $this->manager->addShop([
                        'type' => ShopManager::BUY,
                        'price' => $event->getLine(2),
                        'id' => $item->getId(),
                        'damage' => $item->getDamage(),
                        'vector3' => $block
                    ]);

                    $event->setLine(0, TextFormat::GREEN . " - Buy - ");
                    $event->setLine(1, TextFormat::BLACK . $item->getName());
                    $event->setLine(2, TextFormat::BLACK . "$" . ($event->getLine(2) * 16));

                    $player->sendMessage('&aYou successfully created a shop sign.', true);

                } else if (strtolower($event->getLine(0)) === "sell") {

                    $this->manager->addShop([
                        'type' => ShopManager::SELL,
                        'price' => $event->getLine(2),
                        'id' => $item->getId(),
                        'damage' => $item->getDamage(),
                        'vector3' => $block
                    ]);

                    $event->setLine(0, TextFormat::RED . " - Sell - ");
                    $event->setLine(1, TextFormat::BLACK . $item->getName());
                    $event->setLine(2, TextFormat::BLACK . "$" . ($event->getLine(2) * 16));

                }
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function PlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $diff = HCFLoader::getInstance()->getServer()->getDefaultLevel()->getTileAt($block->x, $block->y, $block->z);
        $data = HCFLoader::getPlayerManager()->getPlayerData();

        if ($diff instanceof Sign) {

            if ($player instanceof HCFPlayer) {

                if ($event->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                    if (HCFLoader::getModulesManager()->getModuleById(ModulesIds::SHOP)->isShop($block)) {
                        $shop = HCFLoader::getModulesManager()->getModuleById(ModulesIds::SHOP)->getShop($block);

                        if ($shop->getType() == 'Sell') {
                            if ($player->getInventory()->contains($shop->getItem())) {
                                $item = null;

                                foreach ($player->getInventory()->getContents() as $number => $iItem) {
                                    if ($iItem->getId() == $shop->getItem()->getId() && $iItem->getDamage() == $shop->getItem()->getDamage()) {
                                        $item = $iItem;
                                    }
                                }

                                if ($item->getCount() < 16) {
                                    HCFLoader::getPlayerManager()->getPlayerData()->setBalance($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) + ($item->getCount() * $shop->getPrice()));

                                    $player->getInventory()->removeItem(Item::get($shop->getItem()->getId(), $shop->getItem()->getDamage(), $item->getCount()));

                                    $diff->setLine(0, TextFormat::GREEN . 'Sold');
                                    $diff->setLine(1, $shop->getItem()->getName());
                                    $diff->setLine(2, TextFormat::GREEN . 'for');
                                    $diff->setLine(3, '$' . ($shop->getPrice() * $item->getCount()));
                                    $this->manager->onChanged($diff, $player);

                                    HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($diff, $shop, $player): void {
                                        $diff->setLine(0, TextFormat::RED . " - Sell - ");
                                        $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                        $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                        $diff->setLine(3, ' ');
                                        $this->manager->onChanged($diff, $player);
                                    }), 10);

                                    return;
                                }

                                $player->getInventory()->removeItem(Item::get($shop->getItem()->getId(), $shop->getItem()->getDamage(), 16));

                                HCFLoader::getPlayerManager()->getPlayerData()->setBalance($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) + (16 * $shop->getPrice()));

                                $diff->setLine(0, TextFormat::GREEN . 'Sold');
                                $diff->setLine(1, $shop->getItem()->getName());
                                $diff->setLine(2, TextFormat::GREEN . 'for');
                                $diff->setLine(3, '$' . ($shop->getPrice() * 16));
                                $this->manager->onChanged($diff, $player);

                                HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                                    function (int $currentTick) use ($diff, $shop, $player): void {
                                        $diff->setLine(0, TextFormat::RED . " - Sell - ");
                                        $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                        $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                        $diff->setLine(3, ' ');
                                        $this->manager->onChanged($diff, $player);
                                    }), 10);

                                return;
                            }
                            $diff->setLine(0, TextFormat::RED . 'You do not');
                            $diff->setLine(1, TextFormat::RED . 'have any');
                            $diff->setLine(2, $shop->getItem()->getName());
                            $diff->setLine(3, TextFormat::RED . 'on you!');
                            $this->manager->onChanged($diff, $player);

                            HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($diff, $shop, $player): void {
                                $diff->setLine(0, TextFormat::RED . " - Sell - ");
                                $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                $diff->setLine(3, ' ');
                                $this->manager->onChanged($diff, $player);
                            }), 10);
                        }

                        if ($shop->getType() == 'Buy') {
                            if ($data->getBalance($player->getName()) > (16 * $shop->getPrice())) {
                                $data->setBalance($player->getName(), $data->getBalance($player->getName()) - (16 * $shop->getPrice()));
                                $player->getInventory()->addItem(Item::get($shop->getItem()->getId(), $shop->getItem()->getDamage(), 16));

                                $diff->setLine(0, TextFormat::GREEN . 'Bought');
                                $diff->setLine(1, $shop->getItem()->getName());
                                $diff->setLine(2, TextFormat::GREEN . 'for');
                                $diff->setLine(3, '$' . ($shop->getPrice() * 16));
                                $this->manager->onChanged($diff, $player);

                                HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($diff, $shop, $player): void {
                                    $diff->setLine(0, TextFormat::GREEN . " - Buy - ");
                                    $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                    $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                    $diff->setLine(3, ' ');
                                    $this->manager->onChanged($diff, $player);
                                }), 10);

                                return;
                            }

                            if ($data->getBalance($player->getName()) < $shop->getPrice()) {
                                $diff->setLine(0, TextFormat::RED . 'Insufficient');
                                $diff->setLine(1, TextFormat::RED . 'funds for');
                                $diff->setLine(2, $shop->getItem()->getName());
                                $this->manager->onChanged($diff, $player);

                                HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($diff, $shop, $player): void {
                                    $diff->setLine(0, TextFormat::GREEN . " - Buy - ");
                                    $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                    $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                    $diff->setLine(3, ' ');
                                    $this->manager->onChanged($diff, $player);
                                }), 10);

                                return;
                            }

                            $diff->setLine(0, TextFormat::GREEN . 'Bought');
                            $diff->setLine(1, $shop->getItem()->getName());
                            $diff->setLine(2, TextFormat::GREEN . 'for');
                            $diff->setLine(3, '$' . $data->getBalance($player->getName()));
                            $this->manager->onChanged($diff, $player);

                            HCFLoader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($diff, $shop, $player): void {
                                $diff->setLine(0, TextFormat::GREEN . " - Buy - ");
                                $diff->setLine(1, TextFormat::BLACK . $shop->getItem()->getName());
                                $diff->setLine(2, TextFormat::BLACK . "$" . ($shop->getPrice() * 16));
                                $diff->setLine(3, ' ');
                                $this->manager->onChanged($diff, $player);
                            }), 10);

                            $player->getInventory()->addItem(Item::get($shop->getItem()->getId(), $shop->getItem()->getDamage(), ($data->getBalance($player->getName()) % $shop->getPrice())));

                            $data->setBalance($player->getName(), 0);
                        }
                    }
                }
            }
        }
    }
}