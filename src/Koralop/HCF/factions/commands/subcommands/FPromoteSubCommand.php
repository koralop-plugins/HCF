<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\factions\FactionManager;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\HCFUtils;
use Koralop\HCF\utils\commands\SubCommand;
use Koralop\HCF\utils\Translate;
use pocketmine\utils\TextFormat;

/**
 * Class FPromoteSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FPromoteSubCommand extends SubCommand
{

    /**
     * FPromoteSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('promote');
        $this->setAliases(['coleader']);
        $this->setUsage('/t ' . $this->getLabel() . ' <playerName>');
    }

    public function execute(HCFPlayer $player, array $args)
    {
        if (!$player->inFaction()) {
            $player->sendMessage(TextFormat::colorize('&7You are not in a team!'));
            return;
        }

        if ($player->getFaction()->getLeader() != $player->getName()) {
            $player->sendMessage(TextFormat::colorize('&cYou must be the leader to do this.'));
            return;
        }

        if (empty($args[0])) {
            $player->sendMessage(TextFormat::RED . $this->getUsage());
            return;
        }

        if (!$player->getFaction()->inFaction($args[0])) {
            $player->sendMessage(TextFormat::colorize('&c' . $args[0] . ' is not in your team.'));
            return;
        }

        $player->getFaction()->addMember($args[0], FactionManager::COLEADER_FACTION);
        $player->sendMessage(TextFormat::colorize('&ePromoted &a' . $args[0] . ' &eto &6Co-Leader.'));

        if (HCFUtils::isOnline($args[0])) {
            HCFLoader::getInstance()->getServer()->getPlayer($args[0])->sendMessage(Translate::getMessage('&eYou have been promoted to &6Captain&e.'));
        }
    }
}