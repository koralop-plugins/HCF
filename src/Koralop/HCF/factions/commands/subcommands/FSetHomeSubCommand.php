<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FSetHomeSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FSetHomeSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('sethome');
        $this->setAliases(['sethq']);
    }

    public function execute(HCFPlayer $player, array $args): void
    {
        if (!$player->inFaction()) {
            $player->sendMessage(TextFormat::GRAY . 'You are not in a team!');
            return;
        }

        if ($player->getFaction()->getLeader() != $player->getName()) {
            $player->sendMessage(TextFormat::RED . 'You are not the leader of your faction!');
            return;
        }

        if ($player->getCurrentClaim() != $player->getFactionName()) {
            $player->sendMessage("&cYou can only set your team''s home in it's own territory.");
            return;
        }

        $player->getFaction()->setHome([$player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel()->getFolderName()]);

        $player->getFaction()->sendMessage("&3" . $player->getName() . " has updated your team's HQ point!");
    }
}