<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\SimpleCommandToDataParser;

final class CommandInfoUpdater{
	use SingletonTrait;

	public function update(Command $cmd, Player $target) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create([SimpleCommandToDataParser::getInstance()->parse($cmd, $target)], [], [], [])
		);
	}
}
