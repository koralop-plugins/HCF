<?php

namespace Koralop\HCF\factions\commands\subcommands;


use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\utils\commands\SubCommand;
use pocketmine\math\Vector3;

/**
 * Class FMapSubCommand
 * @package Koralop\HCF\factions\commands\subcommands
 */
class FMapSubCommand extends SubCommand
{

    /** @var array */
    protected array $see = [];

    /**
     * FMapSubCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('map');
    }

    /**
     * @param HCFPlayer $player
     * @param array $args
     */
    public function execute(HCFPlayer $player, array $args)
    {
        if (isset($this->see[$player->getName()])) {

            HCFLoader::getFactionManager()->seeClaim($player, false);

            $player->sendMessage('&eThe faction map is now &chidden&e.');

            unset($this->see[$player->getName()]);
            return;
        }

        HCFLoader::getFactionManager()->seeClaim($player, true);

        $player->sendMessage('&eThe faction map is now &ashown&e.');

        $this->see[$player->getName()] = '';
    }
}