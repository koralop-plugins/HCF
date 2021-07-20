<?php

namespace Koralop\HCF\modules\partner\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ModulesIds;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class PartnerCommand
 * @package Koralop\HCF\modules\partner\commands
 */
class PartnerCommand extends PluginCommand
{

    /**
     * PPCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('pp', HCFLoader::getInstance());
        $this->setAliases(['pkg']);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof HCFPlayer)
            return;

        if (!$sender->isOp())
            return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/pp edit');
            return;
        }

        switch ($args[0]) {
            case 'edit':
                HCFLoader::getModulesManager()->getModuleById(ModulesIds::PARTNER)->setItems($sender->getInventory()->getContents());
                break;
            case 'give':
                if (empty($args[1])) {
                    return;
                }

                if ($args[1] == 'all') {
                    if (empty($args[2])) {
                        $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/pp give all <amount>');
                        return;
                    }

                    foreach (HCFLoader::getInstance()->getServer()->getOnlinePlayers() as $player) {
                        HCFLoader::getModulesManager()->getModuleById(ModulesIds::PARTNER)->givePartnerPackage($player, $args[2]);
                    }
                    return;
                }

                $player = HCFLoader::getInstance()->getServer()->getPlayer($args[1]);

                if ($player instanceof Player) {
                    if (empty($args[2])) {
                        $sender->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/pp give <playerName> <amount>');
                        return;
                    }

                    HCFLoader::getModulesManager()->getModuleById(ModulesIds::PARTNER)->givePartnerPackage($player, $args[2]);
                    return;
                }

                HCFLoader::getModulesManager()->getModuleById(ModulesIds::PARTNER)->givePartnerPackage($sender, $args[1]);
                break;
        }
    }
}