<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;

/**
 * Class FHomeSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FHomeSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('home');
        $this->setAliases(['hq']);
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

        if ($player->getFaction()->getHome() == null) {
            $player->sendMessage('&cTeam home point is not set.');
            return;
        }

        if ($player->getCooldowns()->getPvPTimer() != null) {
            $player->sendMessage('&cYou cannot teleport to your team home whilst having PvP timer.');
            return;
        }

        if ($player->getCooldowns()->getCombatTag() != null) {
            $player->sendMessage('&cYou cannot teleport to your team home whilst having Spawn Tag.');
            return;
        }

        if (HCFLoader::getFactionManager()->isFaction($player->getCurrentClaim())) {

            if (HCFLoader::getFactionManager()->getFaction($player->getCurrentClaim())->getDtr() == 1000) {
                # Spawn
                $home = $player->getFaction()->getHome();

                $player->teleport(new Position($home['x'], $home['y'], $home['z'], $this->getPLugin()->getServer()->getLevelByName($home['level'])));

                $player->sendMessage(TextFormat::YELLOW . 'Warping to' . TextFormat::LIGHT_PURPLE . $player->getFactionName() . TextFormat::YELLOW . "'s HQ.");
                return;
            }
        }

        $player->getCooldowns()->setHomeTime(HCFLoader::getYamlProvider()->getCooldowns()['home']);

        $player->sendMessage("&eTeleporting to your team's HQ in &d10 seconds&e... Stay still and do not take damage.");
    }
}