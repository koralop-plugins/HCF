<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FAnnounceSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FAnnounceSubCommand extends SubCommand
{

    /**
     * FAnnounceSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('announce');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (!$player->inFaction()) {
            $player->sendMessage(TextFormat::GRAY . 'No team!');
            return;
        }

        if (!$player->getFaction()->isLeader($player->getName())) {
            if (!$player->getFaction()->isCoLeader($player->getName())) {
                $player->sendMessage(TextFormat::colorize('&cYou must be the leader to do this.'));
                return;
            }
        }

        if (empty($args[1])) {
            $player->sendMessage(TextFormat::colorize('&cUsage: /f announce <announce>'));
            return;
        }

        if ($args[1] == 'remove') {
            $player->getFaction()->sendMessage(TextFormat::colorize('&d' . $player->getName() . ' &eremoved the team announcement'));
            $player->getFaction()->setAnnounce('none');
            return;
        }

        unset($args[0]);

        $player->getFaction()->setAnnounce(implode(' ', $args));
        $player->getFaction()->sendMessage(TextFormat::colorize('&d' . $player->getName() . ' &echanged the team announcement to &d' . implode(' ', $args)));
    }
}