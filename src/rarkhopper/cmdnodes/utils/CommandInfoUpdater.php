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

	/**
	 * @param array<Command> $cmds
	 * @param array<Player>  $targets
	 */
	public function update(array $cmds, array $targets) : void{
		foreach($targets as $target){
			$pk = $this->createUpdatePacket($cmds, $target);
			$target->getNetworkSession()->sendDataPacket($pk);
		}
	}

	/**
	 * @param array<Command> $cmds
	 */
	private function createUpdatePacket(array $cmds, Player $target) : AvailableCommandsPacket{
		$parser = CommandToDataParser::getInstance();
		$data = [];

		foreach($cmds as $cmd){
			$data[] = $parser->parse($cmd, $target);
		}

		return AvailableCommandsPacket::create($data, [], [], []);
	}
}
