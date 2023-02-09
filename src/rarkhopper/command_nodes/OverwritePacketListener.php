<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Server;

class OverwritePacketListener implements Listener{
	public function onPacketSend(DataPacketSendEvent $ev) : void{
		foreach($ev->getPackets() as $pk){
			if(!$pk instanceof AvailableCommandsPacket) continue;
			$cmdnodes = CommandNodes::getInstance();
			$cmdMap = $cmdnodes->getCommandMap();

			if(!$cmdMap->needsUpdate()) return;
			$cmdMap->unsetUpdateFlags();
			$cmds = Server::getInstance()->getCommandMap()->getCommands();

			foreach($ev->getTargets() as $target){
				$player = $target->getPlayer();

				if($player === null) continue;
				$cmdnodes->getUpdater()->overwrite(
					$pk,
					$cmdnodes->getParser(),
					$player,
					$cmds
				);
			}
		}

	}
}
