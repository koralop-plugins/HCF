<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;

/**
 * Class FUnFocusSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FUnFocusSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('unfocus');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        if ($player->getFocus() != null) {
            $player->setFocus(null);
        }
    }
}