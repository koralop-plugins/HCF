<?php

namespace Koralop\HCF\factions\commands\subcommands;

use Koralop\HCF\factions\Faction;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\utils\TextFormat;

class FTopSubCommand extends SubCommand
{

    public function __construct()
    {
        parent::__construct('top');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args): void
    {
        $data = [];
        foreach (HCFLoader::getFactionManager()->getAllFactions() as $fName) {
            $data[$fName] = (new Faction($fName))->getPoints();
        }
        $i = 1;

        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));

        foreach ($data as $fName => $points) {
            $player->sendMessage(($i == 1 ? TextFormat::GREEN . $i . TextFormat::GRAY : TextFormat::GRAY . $i) . '. ' . ($fName == $player->getFactionName() ? TextFormat::GREEN . $fName : TextFormat::RED . $fName) . TextFormat::YELLOW . ' - ' . TextFormat::GRAY . $points);

            if ($i > 9) {
                break;
            }
            ++$i;
        }
        $player->sendMessage(TextFormat::GRAY . TextFormat::UNDERLINE . str_repeat('━', 30));

    }
}