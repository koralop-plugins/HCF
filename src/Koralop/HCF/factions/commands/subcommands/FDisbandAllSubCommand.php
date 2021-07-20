<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FDisbandAllSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FDisbandAllSubCommand extends SubCommand
{

    /**
     * FDisbandAllSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('disbandall');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if ($player->isOp()) {
            foreach (HCFLoader::getFactionManager()->getFactions() as $faction) {
                if ($faction->getDtr() < 100) {
                    HCFLoader::getFactionManager()->removeFaction($faction->getName());
                }
            }
        }
    }
}