<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FKickSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FKickSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('kick');
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

        if ($player->getFaction()->getLeader() == $player->getName() or $player->getFaction()->isCoLeader($player->getName())) {
            if (empty($args[1])) {
                $player->sendMessage(TextFormat::colorize('&cUsage: /f kick <member>'));
                return;
            }

            if ($player->getFaction()->inFaction($args[1])) {

                if ($player->getFaction()->isCoLeader($args[1]))
                    return;

                if ($player->getFaction()->isLeader($args[1]))
                    return;

                $player->getFaction()->removeMember($args[1]);
            } else
                $player->sendMessage(TextFormat::colorize('&c' . $args[1] . ' is not in your team.'));
        }
    }
}