<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\CommandToDataParser;

class CommandInfoUpdater{
	use SingletonTrait;

	public function update(Command $cmd, Player $target) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create([CommandToDataParser::getInstance()->parse($cmd, $target)], [], [], [])
		);
	}
}
