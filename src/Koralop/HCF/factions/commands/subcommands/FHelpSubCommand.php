<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FHelpSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FHelpSubCommand extends SubCommand
{

    /**
     * FHelpSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('help');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        $data = [
            "&9General Commands:",
            "&e/t create <teamName> &7- Create a new team",
            "&e/t accept <teamName> &7- Accept a pending invitation",
            "&e/t lives &7- Irreversibly lives to your team",
            "&e/t leave &7- Leave your current team",
            "&e/t home &7- Teleport to your team home",
            "&e/t stuck &7- Teleport out of enemy territory",
            "&e/t deposit <amount|all> &7- Deposit money into your team balance",
            "&7",
            "&9Information Commands:",
            "&e/t who <player|teamName> &7- Display team information",
            "&e/t map &7- Show nearby claims (Indentified by pillars)",
            "&e/t list &7- Show list of teams online (sorted by most online)",
            "&7",
            "&9Captain Commands:",
            "&e/t invite <player> &7- Invite a player to your team",
            "&e/t uninvite <player> &7- Revoke a invitation",
            "&e/t kick <player> &7- Kick a player from your team",
            "&e/f invites &7- Show all invitations",
            "&e/t claim &7- Start a claim for your team",
            "&e/t sethome &7- Set your team's home at your current location",
            "&e/t withdraw <amount> &7- Withdraw money from your team balance",
            "&7",
            "&9Coleader Commands:",
            "&e/f unclaim &7- Remove the faction's claim",
            "&e/f announcement [message] &7- Set the faction's announcement",
            "&7",
            "&9Leader Commands:",
            "&e/t promote <player> &7- Add team coleader",
            "&e/f revive <player> &7- Revive a teammate using faction lives",
            "&e/t rename <newName> &7- Rename your team",
            "&e/t disband &7- Disband your team"
        ];

        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
        $player->sendMessage(TextFormat::BOLD . TextFormat::GOLD . 'Infernal' . TextFormat::RESET . TextFormat::GRAY . ' - ' . TextFormat::YELLOW . 'Team Help');
        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
        foreach ($data as $line => $msg) {
            $player->sendMessage(TextFormat::colorize($msg));
        }
        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));

    }
}