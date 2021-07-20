<?php


namespace Koralop\HCF\modules\npc;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use Koralop\HCF\modules\npc\commands\NPCCommand;
use Koralop\HCF\modules\npc\entity\NPCEntity;
use Koralop\HCF\modules\npc\events\NPCListener;
use pocketmine\entity\Entity;

/**
 * Class NPCManager
 * @package Koralop\HCF\modules\npc
 */
class NPCManager extends Modules
{

    public const NPC = [
        'Block Shop',
        'Team',
        'Kills',
        'KD',
        'Partner Crates'
    ];

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getCommandMap()->register('npc', new NPCCommand());

        Entity::registerEntity(NPCEntity::class, true, ['NPCEntity']);

        $loader->getServer()->getPluginManager()->registerEvents(new NPCListener($this), $loader);
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }

}