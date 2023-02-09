<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use rarkhopper\cmdnodes\CmdNodes;

final class CommandInfoUpdater{
	public function update(Command $cmd, Player $target) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create([CmdNodes::getInstance()->getParser()->parse($cmd, $target)], [], [], [])
		);
	}
}