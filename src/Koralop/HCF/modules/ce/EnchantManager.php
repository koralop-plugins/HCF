<?php

namespace Koralop\HCF\modules\ce;

use Koralop\HCF\HCFLoader;
use Koralop\HCF\modules\ce\commands\EnchantCommand;
use Koralop\HCF\modules\ce\types\Repair;
use Koralop\HCF\modules\ce\types\Speed;
use Koralop\HCF\modules\Modules;
use pocketmine\item\enchantment\Enchantment;

/**
 * Class EnchantManager
 * @package Koralop\HCF\modules\ce
 */
class EnchantManager extends Modules
{

    /**
     * @return Speed[]
     */
    public function getEnchantments(): array
    {
        return [
            'Speed II' => new Speed(),
            'Repair' => new Repair()
        ];
    }

    /**
     * @param HCFLoader $loader
     */
    public function onDisable(HCFLoader $loader): void
    {

    }

    /**
     * @param HCFLoader $loader
     */
    public function onEnable(HCFLoader $loader): void
    {
        $loader->getServer()->getCommandMap()->register('/ce', new EnchantCommand());

        foreach ($this->getEnchantments() as $enchantment) {
            Enchantment::registerEnchantment($enchantment);
        }
    }
}