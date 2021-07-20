<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FUnClaimSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FUnClaimSubCommand extends SubCommand
{

    /**
     * FUnClaimSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('unclaim');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (!$player->inFaction()) {
            $player->sendMessage(TextFormat::GRAY . 'You are not in a team!');
            return;
        }

        if (!$player->getFaction()->isLeader($player->getName())) {
            $player->sendMessage(TextFormat::colorize('&cYou must be the leader to do this.'));
            return;
        }

        if ($player->getFaction()->getDtr() < 0) {
            $player->sendMessage(TextFormat::colorize('&cYou can not unclaim whilst being raidable.'));
            return;
        }

        if ($player->getFaction()->getClaim() == null) {
            $player->sendMessage(TextFormat::RED . 'No have claim!');
            return;
        }

        $player->getFaction()->sendMessage('&eFaction claim has been voided by &d' . $player->getName() . '.');
        $player->getFaction()->removeClaim();
        $player->sendMessage(TextFormat::colorize('&eSuccesfully unclaimed.'));
    }
}