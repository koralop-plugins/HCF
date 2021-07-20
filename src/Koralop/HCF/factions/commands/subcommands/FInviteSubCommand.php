<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FInviteSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FInviteSubCommand extends SubCommand
{

    /**
     * FInviteSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('invite');
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

        if ($player->getFaction()->getLeader() != $player->getName()) {
            $player->sendMessage(TextFormat::colorize('&cYou must be the leader to do this.'));
            return;
        }

        if (empty($args[1])) {
            $player->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/f invite <playerName>');
            return;
        }

        $invite = $this->getPLugin()->getServer()->getPlayer($args[1]);

        if ($invite instanceof HCFPlayer) {

            if ($invite->inFaction()) {
                $player->sendMessage(TextFormat::RED . $invite->getName() . ' is already in a faction');
                return;
            }

            $invite->addInvite($player->getFactionName());

            $invite->sendMessage(
                TextFormat::BLUE . $player->getName() . ' invited you to join ' . "'" . TextFormat::YELLOW . $player->getFactionName() . TextFormat::BLUE . "'." . TextFormat::EOL .
                TextFormat::BLUE . "Type '" . TextFormat::YELLOW . '/f join ' . $player->getFactionName() . TextFormat::BLUE . "'"
            );
        } else {
            $player->sendMessage(TextFormat::RED . 'The player is not online!');
        }
    }
}