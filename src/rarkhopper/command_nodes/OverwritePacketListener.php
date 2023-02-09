<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class OverwritePacketListener implements Listener{
	public function onPacketSend(DataPacketSendEvent $ev) : void{
		$cmdnodes = CommandNodes::getInstance();

		foreach($ev->getTargets() as $target){
			$player = $target->getPlayer();

			if($player === null) continue;
			foreach($ev->getPackets() as $pk){
				if(!$pk instanceof AvailableCommandsPacket) continue;
				$cmdnodes->getUpdater()->inject(
					$pk,
					$cmdnodes->getParser(),
					$player,
					$cmdnodes->getCommandMap()->getHasUpdateCommands()
				);
			}
		}
	}
}
