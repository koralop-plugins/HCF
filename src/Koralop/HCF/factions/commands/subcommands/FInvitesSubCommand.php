<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FInvitesSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FInvitesSubCommand extends SubCommand
{

    /**
     * FInvitesSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('invites');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (count($player->getInvite()) == 0) {
            $player->sendMessage('');
            return;
        }

        $player->sendMessage(' &eInvitations: ' . implode(', ', $player->getInvite()));
    }
}