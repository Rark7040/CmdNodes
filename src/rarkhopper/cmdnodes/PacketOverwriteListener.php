<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Server;

class PacketOverwriteListener implements Listener{
	public function onPacketSend(DataPacketSendEvent $ev) : void{
		$parser = CommandToDataParser::getInstance();

		foreach($ev->getPackets() as $pk){
			if(!$pk instanceof AvailableCommandsPacket) continue;
			foreach($ev->getTargets() as $session){
				$player = $session->getPlayer();

				if($player === null) continue;
				$data = [];

				foreach(Server::getInstance()->getCommandMap()->getCommands() as $cmd){
					$data[] = $parser->parse($cmd, $player);
				}
				$pk->commandData = $data;
			}
		}
	}
}
