<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FLivesSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FLivesSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('lives');
    }

    public function execute(HCFPlayer $player, array $args)
    {
        if (empty($args[1])) {
            $player->sendMessage(TextFormat::colorize('&cUsage: /f lives <deposit|withdraw> <amount> '));
            return;
        }

        switch ($args[1]) {
            case 'deposit':
            case 'd':
                if (empty($args[2])) {
                    $player->sendMessage(TextFormat::colorize('&cUsage: /f lives deposit <amount> '));
                    return;
                }

                if (!is_nan($args[2])) {
                    return;
                }

                if (HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName()) == 0) {
                    $player->sendMessage(TextFormat::colorize('&cYou do not have any lives to deposit to the Faction.'));
                    return;
                }

                if (HCFLoader::getPlayerManager()->getPlayerData()->getLives($player->getName()) < $args[2]) {
                    $player->sendMessage(TextFormat::colorize('&cYou do not have ' . $args[2] . ' lives.'));
                    return;
                }

                $player->getFaction()->setLives($args[2]);

                $player->sendMessage(TextFormat::colorize('&aDeposited ' . $args[2] . ' lives to the Faction.'));
                $player->getFaction()->sendMessage(TextFormat::colorize('&6' . $player->getName() . ' &edeposited &a' . $args[2] . ' &elives to the faction.'));
                break;
            case 'withdraw':
        }
    }
}