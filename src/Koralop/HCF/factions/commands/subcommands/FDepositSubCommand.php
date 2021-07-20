<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FDepositSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FDepositSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('deposit');
        $this->setAliases(['d']);
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

        if ($args[1] == 'all' or $args[1] == 'All') {
            $player->getFaction()->addBalance(HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()));

            $player->sendMessage(TextFormat::YELLOW . 'You have added ' . TextFormat::LIGHT_PURPLE . HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) . TextFormat::YELLOW . ' to the team balance!');
            $player->getFaction()->sendMessage(TextFormat::YELLOW . $player->getName() . ' deposited ' . TextFormat::LIGHT_PURPLE . HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) . TextFormat::YELLOW . ' into the team balance.');

            HCFLoader::getPlayerManager()->getPlayerData()->setBalance($player->getName(), 0);
            return;
        }

        if (HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) >= $args[1]) {
            $player->getFaction()->addBalance($args[1]);

            $player->sendMessage(TextFormat::YELLOW . 'You have added ' . TextFormat::LIGHT_PURPLE . $args[1] . TextFormat::YELLOW . ' to the team balance!');
            $player->getFaction()->sendMessage(TextFormat::YELLOW . $player->getName() . ' deposited ' . TextFormat::LIGHT_PURPLE . '$' . $args[1] . TextFormat::YELLOW . ' into the team balance.');

            HCFLoader::getPlayerManager()->getPlayerData()->setBalance($player->getName(), HCFLoader::getPlayerManager()->getPlayerData()->getBalance($player->getName()) - $args[1]);
        }
    }
}