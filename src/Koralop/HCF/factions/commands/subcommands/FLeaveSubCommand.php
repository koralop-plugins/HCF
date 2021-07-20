<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FLeaveSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FLeaveSubCommand extends SubCommand
{

    /**
     * FLeaveSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('leave');
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
        if ($player->getFaction()->getLeader() == $player->getName()) {
            $player->sendMessage(TextFormat::RED . 'You cannot leave a faction you lead.');
            return;
        }

        $player->sendMessage(TextFormat::colorize('&eYou have left your team.'));
        $player->getFaction()->sendMessage(TextFormat::colorize('&e' . $player->getName() . ' has left the team.'));
        $player->getFaction()->removeMember($player->getName());
    }
}