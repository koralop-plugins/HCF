<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\HCFUtils;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FReviveSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FReviveSubCommand extends SubCommand
{

    /**
     * FReviveSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('revive');
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
            $player->sendMessage('&cUsage: /f revive <playerName>');
            return;
        }

        if (!$player->getFaction()->inFaction($args[1])) {
            $player->sendMessage('&c' . $args[1] . ' is not in your team.');
            return;
        }

        if (HCFUtils::isOnline($args[1])) {

            $fPlayer = HCFUtils::getPlayer($args[1]);

            if ($fPlayer->getCooldowns()->getDeathbanTime() == null) {
                $player->sendMessage('&cNo death-ban found for ' . $args[1] . '.');
                return;
            }

            if ($player->getFaction()->getLives() == 0) {
                $player->sendMessage('&cYour team does not have any lives.');
                return;
            }

            $fPlayer->getCooldowns()->setDeathbanTime(1);

            $player->sendMessage("&aRemoved " . $args[0] . "''s death-ban.");

            $player->getFaction()->setLives($player->getFaction()->getLives() - 1);
        } else
            $player->sendMessage('&c' . $args[1] . ' is not currently online.');
    }
}