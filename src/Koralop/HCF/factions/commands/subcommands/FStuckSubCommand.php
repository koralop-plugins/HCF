<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FStuckSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FStuckSubCommand extends SubCommand
{

    /**
     * FStuckSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('stuck');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        $player->getCooldowns()->setStuckTime(HCFLoader::getYamlProvider()->getCooldowns()['stuck']);
    }
}