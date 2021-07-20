<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

class FForceDisbandSubCommand extends SubCommand
{

    /**
     * FForceDisbandSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('forcedisband');
    }


    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {

        if (!$player->hasPermission('fforcedisband.command.use'))
            return;

        if (empty($args[0])) {
            $player->sendMessage(TextFormat::RED . 'Usage: /f forcedisband <factionName>');
            return;
        }

        if (!HCFLoader::getFactionManager()->isFaction($args[0])) {
            $player->sendMessage(TextFormat::RED . 'This faction does not exist');
            return;
        }

        $this->getPLugin()->getServer()->broadcastMessage(TextFormat::YELLOW . 'Team ' . TextFormat::BLUE . $args[0] . TextFormat::YELLOW . ' has ben' . TextFormat::RED . ' deleted ' . TextFormat::YELLOW . 'by ' . TextFormat::GOLD . $player->getName());

        HCFLoader::getFactionManager()->removeFaction($args[0]);
    }
}
