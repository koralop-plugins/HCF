<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

class FDeleteSubCommand extends SubCommand
{

    /**
     * FDeleteSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('delete');
        $this->setAliases(['disband']);
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

        if ($player->getFaction()->getDtr() < 0) {
            $player->sendMessage(TextFormat::colorize('&cYou cannot do this while your team is raidable.'));
            return;
        }

        if ($player->getFaction()->getFreezeTime() != null) {
            $player->sendMessage(TextFormat::colorize('&cYou cannot join this team because they are on dtr freeze.'));
            return;
        }

        $player->getFaction()->sendMessage('&l&c' . $player->getName() . ' has disbanded the team.');
        $this->getPLugin()->getServer()->broadcastMessage(TextFormat::YELLOW . 'Team ' . TextFormat::BLUE . $player->getFactionName() . TextFormat::YELLOW . ' has ben' . TextFormat::RED . ' deleted ' . TextFormat::YELLOW . 'by ' . TextFormat::GOLD . $player->getName());

        HCFLoader::getFactionManager()->removeFaction($player->getFactionName());
    }
}