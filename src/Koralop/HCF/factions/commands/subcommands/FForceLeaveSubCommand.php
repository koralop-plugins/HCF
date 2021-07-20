<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FForceLeaveSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FForceLeaveSubCommand extends SubCommand
{

    /**
     * FForceLeaveSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('forceleave');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        if (!$player->hasPermission('fforceleave.command.use')) {
            return;
        }

        if (empty($args[1]) or empty($args[2])) {
            $player->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/f forceleave <playerName> <factionName>');
            return;
        }

        if (!HCFLoader::getFactionManager()->isFaction($args[2])) {
            $player->sendMessage(TextFormat::RED . 'The faction does not exist!');
            return;
        }

        $f = HCFLoader::getFactionManager()->getFaction($args[2]);

        if ($f->inFaction($args[1])) {
            $player->sendMessage(TextFormat::RED . 'The member is not in the faction');
            return;
        }

        $f->removeMember($args[1]);
        $player->sendMessage(TextFormat::RED . 'Faction member has been successfully removed');
    }
}