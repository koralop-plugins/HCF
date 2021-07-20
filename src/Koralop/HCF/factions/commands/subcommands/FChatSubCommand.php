<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FChatSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FChatSubCommand extends SubCommand
{

    /**
     * FChatSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('chat');
        $this->setAliases(['c']);
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

        if (empty($args[1])) {
            return;
        }

        switch ($args[1]) {
            case 'f':
            case 'faction':
                $player->setChat(HCFPlayer::FACTION_CHAT);

                $player->sendMessage('&eYou are now chatting in ' . $args[1] . '&e');
                break;
            case 'p':
            case 'public':
                $player->setChat(HCFPlayer::PUBLIC_CHAT);

                $player->sendMessage('&eYou are now chatting in ' . $args[1] . '&e');
                break;
            case 'ally':
            case 'a':
                $player->setChat(HCFPlayer::ALLY_CHAT);

                $player->sendMessage('&eYou are now chatting in ' . $args[1] . '&e');
                break;
        }

        $player->sendMessage('&c' . $args[1] . ' is an invalid chat type.');
    }
}