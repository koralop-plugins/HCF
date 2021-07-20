<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

/**
 * Class FRenameSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FRenameSubCommand extends SubCommand
{

    /**
     * FRenameSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('rename');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (!$player->inFaction()) {
            $player->sendMessage('&7You are not in a team!');
            return;
        }

        if (!$player->getFaction()->isLeader($player->getName())) {
            $player->sendMessage('&cYou must be the leader to do this.');
            return;
        }

        if (empty($args[1])) {
            $player->sendMessage('&cUsage: /f rename <newName>');
            return;
        }

        if (HCFLoader::getFactionManager()->isFaction($args[1])) {
            $player->sendMessage(TextFormat::RED . 'The faction already exists!');
            return;
        }

        if (strlen($args[1]) <= 3) {
            return;
        }

        $oldName = $player->getFactionName();

        rename(HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $player->getFactionName() . '.yml', HCFLoader::getInstance()->getDataFolder() . 'factions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $args[1] . '.yml');

        HCFLoader::getFactionManager()->rename($oldName, $args[1]);

        Server::getInstance()->broadcastMessage(TextFormat::colorize('&eTeam &9' . $oldName . ' &ehas been &6renamed &eto &f' . $args[1]));
    }
}