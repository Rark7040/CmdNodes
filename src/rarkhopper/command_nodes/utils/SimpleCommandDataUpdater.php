<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

final class SimpleCommandDataUpdater implements ICommandDataUpdater{
	public function update(ICommandToDataParser $parser, Command $cmd, Player $target) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create([$parser->parse($cmd, $target)], [], [], [])
		);
	}

	public function updateAll(ICommandToDataParser $parser, Player $target, Command ...$cmds) : void{
		$cmdDataPool = [];

		foreach($cmds as $cmd){
			$cmdDataPool[] = $parser->parse($cmd, $target);
		}
		$target->getNetworkSession()->sendDataPacket(AvailableCommandsPacket::create($cmdDataPool, [], [], []));
	}
}
