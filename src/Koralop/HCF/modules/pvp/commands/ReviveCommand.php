<?php

namespace Koralop\HCF\modules\pvp\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\ModulesIds;
use Koralop\HCF\utils\commands\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

/**
 * Class ReviveCommand
 * @package Koralop\HCF\modules\pvp\commands
 */
class ReviveCommand extends Command
{

    /**
     * ReviveCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('revive', HCFLoader::getInstance());
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

        if (empty($args[0])) {
            $sender->sendMessage('&cUsage: /revive <player>', true);
            return;
        }

        $player = HCFLoader::getInstance()->getServer()->getPlayer($args[0]);

        if (!$player instanceof HCFPlayer) {
            $sender->sendMessage('&cNo death-ban found for ' . $args[0] . '.', true);
            return;
        }

        if (!HCFLoader::getModulesManager()->getModuleById(ModulesIds::PVP)->isDeathBan($player)) {
            $sender->sendMessage('&cNo death-ban found for ' . $args[0] . '.', true);
            return;
        }

        if (HCFLoader::getPlayerManager()->getPlayerData()->getLives($sender->getName()) < 1) {
            $sender->sendMessage('&cYou do not have any lives', true);
            return;
        }

        $sender->sendMessage('&aRemoved ' . $args[0] . "'s death-ban.", true);

        $player->getCooldowns()->setDeathbanTime(1);
    }
}