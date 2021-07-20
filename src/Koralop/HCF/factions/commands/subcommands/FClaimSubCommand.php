<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FClaimSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FClaimSubCommand extends SubCommand
{

    /**
     * FClaimSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('claim');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {

        if (!$player->inFaction()) {
            $player->sendMessage(TextFormat::GRAY . 'You are not in a team!');
            return;
        }

        $faction = HCFLoader::getFactionManager()->getFaction($player->getFactionName());

        if ($faction->getLeader() != $player->getName()) {
            $player->sendMessage(TextFormat::colorize('&cYou must be the leader to do this.'));
            return;
        }

        $player->addTool();
        $player->setClaimInteract(true);
    }
}