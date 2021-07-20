<?php

namespace Koralop\HCF\modules\logout\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

/**
 * Class LogoutCommand
 * @package Koralop\HCF\modules\logout\commands
 */
class LogoutCommand extends PluginCommand
{

    /**
     * LogoutCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('logout', HCFLoader::getInstance());
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

        if ($sender->getCooldowns()->getCombatTag() != null) {
            return;
        }

        if ($sender->getCooldowns()->getLogoutTime() != null) {
            return;
        }

        $sender->getCooldowns()->setLogoutTime(HCFLoader::getYamlProvider()->getCooldowns()['logout']);
    }
}