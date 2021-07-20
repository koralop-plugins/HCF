<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\factions\FactionManager;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FJoinSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FJoinSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('join');

        $this->setAliases(['accept']);
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        if (empty($args[1])) {
            $player->sendMessage('&cUsage: /f join <factionName>');
            return;
        }

        foreach ($player->getInvite() as $faction) {
            if ($args[1] == $faction) {

                if ($player->inFaction())
                    return;
                
                $f = HCFLoader::getFactionManager()->getFaction($faction);

                if (count($f->getPlayers()) >= HCFLoader::getYamlProvider()->getFactionConfig()['members']['max']) {
                    $player->sendMessage('&cThis team already has the max members allowed.');
                    return;
                }


                $f->addMember($player->getName(), FactionManager::MEMBER_FACTION);

                $f->sendMessage('&e' . $player->getName() . ' has joined the team.');
                return;
            }
        }

        $player->sendMessage('&cThis team has not invited you.');
    }
}