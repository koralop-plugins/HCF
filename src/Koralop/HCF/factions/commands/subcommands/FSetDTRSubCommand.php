<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FSetDTRSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FSetDTRSubCommand extends SubCommand
{

    /**
     * FSetDTRSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('setdtr');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        if (!$player->hasPermission('fsetdtr.command.use')) {
            return;
        }

        if (empty($args[1]) or empty($args[2])) {
            $player->sendMessage(TextFormat::RED . 'Usage: ' . TextFormat::GRAY . '/f setdtr <factionName> <dtr>');
            return;
        }

        if (!HCFLoader::getFactionManager()->isFaction($args[1])) {
            $player->sendMessage(TextFormat::RED . 'The faction does not exist!');
            return;
        }

        $f = HCFLoader::getFactionManager()->getFaction($args[1]);

        $f->setDtr($args[2]);
    }
}