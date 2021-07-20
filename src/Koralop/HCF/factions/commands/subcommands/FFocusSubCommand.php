<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FFocusSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FFocusSubCommand extends SubCommand
{

    /**
     * FFocusSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('focus');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {

        if (empty($args[1])) {
            return;
        }

        if (!HCFLoader::getFactionManager()->isFaction($args[1])) {
            return;
        }

        $player->setFocus($args[1]);
    }
}