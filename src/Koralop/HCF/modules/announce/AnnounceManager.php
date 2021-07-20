<?php

namespace Koralop\HCF\modules\announce;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\Modules;
use pocketmine\utils\TextFormat;

/**
 * Class AnnounceManager
 * @package Koralop\HCF\modules\announce
 */
class AnnounceManager extends Modules
{
    /** @var string */
    public const PREFIX = ' ' . TextFormat::EOL . TextFormat::DARK_GRAY . '[' . TextFormat::BOLD . TextFormat::GOLD . 'Tip' . TextFormat::RESET . TextFormat::DARK_GRAY . '] ' . TextFormat::YELLOW;

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return [
            'You can recive a Challenge Crate Key by completing your /challenge.',
            'You can equip the Mage Class by putting on a gold helmet, chain chestplate and leggings, and gold boots.',
            'Interesed in purchasing Partner Packages, Keys and more? Mead over to store.HCF.net',
            'Did you know you can win free loot by voting? Type /vote!',
            'In need of Glowstone and Gunpowder? Head over to Glowstone Mountain which is located in the Nether.',
            'Join our Discord for announcements, giveaways, and more: ' . TextFormat::LIGHT_PURPLE . 'discord.gg/vipermc',
            'Once you regenerate DTR after going raideable, your claim will rebuild yourself.',
            'Once you leave End Safezone, you cannot re-enter it. Be careful!',
            'You can pearl through open fence gates, but no string.',
            'You lose 0.75 DTR in the End and Nether.'
        ];
    }

    /**
     * @return string
     */
    public function randMessage(): string
    {
        return ($this->getMessages()[array_rand($this->getMessages())]) . TextFormat::EOL . ' ';
    }

    public function onDisable(HCFLoader $loader): void
    {
        // TODO: Implement onDisable() method.
    }

    public function onEnable(HCFLoader $loader): void
    {
        // TODO: Implement onEnable() method.
    }
}