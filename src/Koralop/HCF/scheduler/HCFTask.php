<?php

namespace Koralop\HCF\scheduler;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\announce\AnnounceManager;
use Koralop\HCF\modules\ce\Enchant;
use Koralop\HCF\modules\kit\KitIds;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\modules\wayPoint\WayPoint;
use pocketmine\entity\Human;
use pocketmine\item\ItemIds;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

/**
 * Class HCFTask
 * @package Koralop\HCF\scheduler
 */
class HCFTask extends Task
{
    /** @var int */
    protected int $time = 0;

    /** @var HCFLoader */
    protected HCFLoader $loader;

    /**
     * HCFTask constructor.
     * @param HCFLoader $loader
     */
    public function __construct(HCFLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        if ($this->time == (60 * 5)) {
            HCFLoader::getInstance()->getServer()->broadcastMessage(AnnounceManager::PREFIX . HCFLoader::getModulesManager()->getModuleById(ModulesIds::ANNOUNCE)->randMessage());

            $this->time = 0;
        }

        foreach (HCFLoader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if ($player instanceof HCFPlayer) {
                foreach (HCFLoader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer) {

                    if ($onlinePlayer instanceof HCFPlayer) {

                        if ($player->inFaction()) {

                            if ($onlinePlayer->inFaction()) {

                                if ($player->getFactionName() == $onlinePlayer->getFactionName()) {

                                    $player->sendData($onlinePlayer, [
                                        Human::DATA_NAMETAG => [
                                            Human::DATA_TYPE_STRING,
                                            TextFormat::GOLD . '[' . TextFormat::GREEN . $player->getFactionName() . TextFormat::GRAY . ' | ' . TextFormat::RED . $player->getFaction()->getDtr() . TextFormat::GOLD . ']' .
                                            TextFormat::EOL . TextFormat::GREEN . $player->getName()
                                        ]
                                    ]);
                                }

                            } else {

                                $player->sendData($onlinePlayer, [
                                    Human::DATA_NAMETAG => [
                                        Human::DATA_TYPE_STRING,
                                        TextFormat::GOLD . '[' . TextFormat::RED . $player->getFactionName() . TextFormat::GRAY . ' | ' . TextFormat::RED . $player->getFaction()->getDtr() . TextFormat::GOLD . ']' .
                                        TextFormat::EOL . TextFormat::RED . $player->getName()
                                    ]
                                ]);
                            }

                        } else {
                            $player->sendData($onlinePlayer, [
                                Human::DATA_NAMETAG => [
                                    Human::DATA_TYPE_STRING,
                                    TextFormat::EOL . TextFormat::RED . $player->getName()
                                ]
                            ]);

                        }
                    }
                }


                if ($player->getArmorInventory()->getHelmet()->getId() == ItemIds::AIR or $player->getArmorInventory()->getChestplate()->getId() == ItemIds::AIR or $player->getArmorInventory()->getLeggings()->getId() == ItemIds::AIR or $player->getArmorInventory()->getBoots()->getId() == ItemIds::AIR)
                    $player->setClass(null);

                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById(KitIds::ARCHER)->check($player);
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById(KitIds::MAGE)->check($player);
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById(KitIds::MINER)->check($player);
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById(KitIds::BARD)->check($player);
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->getClassById(KitIds::ROGUE)->check($player);

                foreach ($player->getArmorInventory()->getHelmet()->getEnchantments() as $enchantment) {
                    if ($enchantment->getType() instanceof Enchant) {
                        $enchantment->getType()->onActivate($player);
                    }
                }

                foreach ($player->getArmorInventory()->getChestplate()->getEnchantments() as $enchantment) {
                    if ($enchantment->getType() instanceof Enchant) {
                        $enchantment->getType()->onActivate($player);
                    }
                }

                foreach ($player->getArmorInventory()->getLeggings()->getEnchantments() as $enchantment) {
                    if ($enchantment->getType() instanceof Enchant) {
                        $enchantment->getType()->onActivate($player);
                    }
                }

                foreach ($player->getArmorInventory()->getBoots()->getEnchantments() as $enchantment) {
                    if ($enchantment->getType() instanceof Enchant) {
                        $enchantment->getType()->onActivate($player);
                    }
                }
            }
        }
        $this->time++;
    }
}