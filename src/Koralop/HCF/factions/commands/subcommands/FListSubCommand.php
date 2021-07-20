<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

/**
 * Class FListSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FListSubCommand extends SubCommand
{

    /**
     * FListSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('list');
    }

    public function execute(HCFPlayer $player, array $args)
    {
        $factions = [];
        foreach (HCFLoader::getFactionManager()->getAllFactions() as $faction) {
            $factions[] = HCFLoader::getFactionManager()->getFaction($faction);
        }
        if (!arsort($factions)) {
            $player->sendMessage('Error 404 report in discord: discord.gg/HCF');
            return;
        }
        $page = 1;

        if (isset($args[1]))
            $page = (int)$args[1];


        $pages = ceil(count($factions) / 10);

        if ((!is_numeric($page)) or $page > $pages)
            $page = $pages;


        $factions = array_slice($factions, ($page - 1) * 10, 10);
        $place = (($page - 1) * 10) + 1;

        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
        $player->sendMessage(TextFormat::BLUE . 'Team List ' . TextFormat::GRAY . '(Page ' . $page . '/' . $pages . ')');
        foreach ($factions as $faction) {
            $player->sendMessage(TextFormat::GRAY . "$place. " . TextFormat::YELLOW . $faction->getName() . TextFormat::GREEN . ' (' . $faction->getCountOnlinePLayers() . '/' . count($faction->getPlayers()) . ')');
            ++$place;
        }
        $player->sendMessage(TextFormat::GRAY . 'You are currently on ' . TextFormat::WHITE . 'Page ' . $page . '/' . $pages);
        $player->sendMessage(TextFormat::GRAY . 'To view other pages, use ' . TextFormat::YELLOW . '/t list <page>');
        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));
    }
}