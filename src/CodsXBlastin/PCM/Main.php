<?php

declare(strict_types=1);

namespace THXC;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;
use pocketmine\inventory\CraftingGrid;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use pocketmine\block\Block;
use function array_key_exists;

class Main extin extends PluginBase{

    public function sendCraftingTable(Player $player)
    {
        $block1 = Block::get(Block::CRAFTING_TABLE);
        $block1->x = (int)floor($player->x);
        $block1->y = (int)floor($player->y) - 2;
        $block1->z = (int)floor($player->z);
        $block1->level = $player->getLevel();
        $block1->level->sendBlocks([$player], [$block1]);
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
    {
        switch($command->getName())
        {
            case "craft":
                if($sender instanceof Player)
                {
                  $this->sendCraftingTable($sender);
                   $sender->setCraftingGrid(new CraftingGrid($sender, CraftingGrid::SIZE_BIG));
                   if(!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows))
                   {
                   $pk = new ContainerOpenPacket();
                   $pk->windowId = $windowId;
                   $pk->type = WindowTypes::WORKBENCH;
                   $pk->x = $sender->getFloorX();
                   $pk->y = $sender->getFloorY() - 2;
                   $pk->z = $sender->getFloorZ();
                   $sender->sendDataPacket($pk);
                   $sender->openHardcodedWindows[$windowId] = true;
                   }
                }
                return true;
            default:
                return false;
        }
    }
}
