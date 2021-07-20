<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\HCFUtils;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FAllySubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FAllySubCommand extends SubCommand
{

    /**
     * FAllySubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('ally');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (!$player->inFaction()) {
            $player->sendMessage('&7You are not in a team!');
            return;
        }

        if (!$player->getFaction()->isLeader($player->getName())) {
            $player->sendMessage('&cYou must be the leader to do this.');
            return;
        }

        if (empty($args[1])) {
            $player->sendMessage('&cUsage: /f ally <list|add|accept> <factionName>');
            return;
        }

        switch ($args[1]) {
            case 'add':

                if (empty($args[2])) {
                    $player->sendMessage('&cUsage: /f ally add <factionName>');
                    return;
                }

                if (!HCFLoader::getFactionManager()->isFaction($args[2])) {
                    return;
                }

                $f = HCFLoader::getFactionManager()->getFaction($args[2]);

                if (count($f->getAllys()) >= HCFLoader::getYamlProvider()->getFactionConfig()['allies']['max']) {
                    $player->sendMessage('&6Your faction or ' . $args[2] . ' &ehas already reached the max allies limit.');
                    return;
                }

                if (count($player->getFaction()->getAllys()) >= HCFLoader::getYamlProvider()->getFactionConfig()['allies']['max']) {
                    $player->sendMessage('&6Your faction or ' . $args[2] . ' &ehas already reached the max allies limit.');
                    return;
                }

                if ($f->isInviteAlly($player->getFactionName())) {
                    $player->sendMessage('&cYou are already allied to this faction or the request is pending.');
                    return;
                }


                if (HCFUtils::isOnline($f->getLeader())) {
                    $leader = HCFUtils::getPlayer($f->getLeader());

                    $leader->sendMessage('&6' . $player->getFactionName() . ' &ewishes to be your ally.');
                }

                $f->addInviteAlly($player->getFactionName());

                $player->sendMessage('&eYou sent an ally request to &6' . $args[2] . '&e.');
                break;
            case 'list':
                $player->sendMessage('&eInvitations for allys: &f' . implode(', ', $player->getFaction()->getInviteAlly()));
                break;
            case 'accept':
                if (empty($args[2])) {
                    $player->sendMessage('&cUsage: /f ally accept <factionName>');
                    return;
                }

                if (!$player->getFaction()->isInviteAlly($args[2])) {
                    return;
                }

                $player->getFaction()->deleteInviteAlly($args[2]);

                $player->getFaction()->setAllys($player->getFaction()->getAllys() + [$args[2]]);

                $player->sendMessage('&eYour faction is now allied with &d' . $args[2] . '&e.');
                break;
        }
    }
}