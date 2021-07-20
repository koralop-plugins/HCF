<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;

use pocketmine\utils\TextFormat;
/**
 * Class FCreateCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FCreateCommand extends SubCommand
{

    /**
     * FCreateCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('create');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        if (empty($args[1])) {
            $player->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/f create <factionName>');
            return;
        }

        if ($player->inFaction()) {

            return;
        }

        if (HCFLoader::getFactionManager()->isFaction($args[1])) {
            if (!$player->isGod()) {
                $player->sendMessage(TextFormat::RED . 'The faction already exists!');
                return;
            }
        }

        HCFLoader::getFactionManager()->addFaction($args[1], $player);

        $this->getPLugin()->getServer()->broadcastMessage(TextFormat::YELLOW . 'Team ' . TextFormat::BLUE . $args[1] . TextFormat::YELLOW . ' has ben' . TextFormat::GREEN . ' created ' . TextFormat::YELLOW . 'by ' . TextFormat::GOLD . $player->getName());
    }
}