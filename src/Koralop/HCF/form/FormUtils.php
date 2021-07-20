<?php


namespace Koralop\HCF\form;

use Koralop\HCF\form\utils\CustomForm;
use Koralop\HCF\HCFLoader;
use Koralop\HCF\HCFPlayer;

use Koralop\HCF\modules\ModulesIds;
use pocketmine\utils\TextFormat;
/**
 * Class FormUtils
 * @package Koralop\HCF\form
 */
final class FormUtils
{


    /**
     * @param HCFPlayer $player
     */
    public static function getCreateKitForm(HCFPlayer $player): void
    {
        $form = new CustomForm(function (HCFPlayer $player, $data = null): bool {
            HCFLoader::getModulesManager()->getModuleById(ModulesIds::KIT)->addKit([
                'name' => $data[0],
                'format' => $data[1],
                'permission' => $data[2],
                'slot' => $data[3],
                'item' => $data[4],
                'items' => $player->getInventory()->getContents(),
                'armorItems' => $player->getArmorInventory()->getContents()
            ]);

            return true;
        });
        $form->setTitle(TextFormat::GOLD . 'Kit Create');
        $form->addInput('Kit Name');
        $form->addInput('Kit Format');
        $form->addInput('Kit Permission');
        $form->addInput('Kit Slot');
        $form->addInput('Kit Item');

        $player->sendForm($form);
    }

    public static function addTimer(HCFPlayer $player): void
    {
        $form = new CustomForm(function (HCFPlayer $player, $data = null): bool {
            HCFLoader::getModulesManager()->getModuleById(ModulesIds::TIMER)->addTimer([
                'name' => $data[0],
                'time' => $data[1],
                'format' => str_replace('&', '§', $data[2])
            ]);
            return true;
        });
        $form->setTitle(TextFormat::GOLD . 'Create Timer');
        $form->addInput('Name');
        $form->addInput('Time');
        $form->addInput('Format');

        $player->sendForm($form);
    }
}