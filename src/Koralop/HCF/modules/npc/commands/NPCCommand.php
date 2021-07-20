<?php

namespace Koralop\HCF\modules\npc\commands;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;
use Koralop\HCF\modules\npc\entity\NPCEntity;
use Koralop\HCF\modules\npc\NPCManager;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\plugin\Plugin;

class NPCCommand extends PluginCommand
{

    public function __construct()
    {
        parent::__construct('npc', HCFLoader::getInstance());
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof HCFPlayer)
            return;

        if (empty($args[0])) {
            return;
        }

        if (!$sender->isOp())
            return;

        switch ($args[0]) {
            case 'spawn':
                if (empty($args[1])) {
                    return;
                }

                if (!in_array($args[1], NPCManager::NPC)) {
                    return;
                }

                $nbt = Entity::createBaseNBT(new Vector3((float)$sender->getX(), (float)$sender->getY(), (float)$sender->getZ()));
                $nbt->setTag(clone $sender->namedtag->getCompoundTag('Skin'));
                $nbt->setString('type', $args[1]);

                $human = new NPCEntity($sender->getLevel(), $nbt);
                $human->setNameTag('');
                $human->setNameTagVisible(false);
                $human->setNameTagAlwaysVisible(false);
                $human->yaw = $sender->getYaw();
                $human->pitch = $sender->getPitch();
                $human->spawnToAll();
                break;
            case 'remove':

                if (empty($args[1])) {
                    return;
                }

                if ($args[1] == 'all') {
                    foreach (HCFLoader::getInstance()->getServer()->getDefaultLevel()->getEntities() as $entity) {
                        if ($entity instanceof NPCEntity) {
                            $entity->close();
                        }
                    }
                }
                break;
        }
    }
}