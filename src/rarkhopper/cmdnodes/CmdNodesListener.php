<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class CmdNodesListener implements Listener{

	/**
	 * @priority MONITOR
	 */
	public function onPacketSend(DataPacketSendEvent $ev) : void{

		$pks = $ev->getPackets();
		foreach ($pks as $pk) {
			if ($pk instanceof AvailableCommandsPacket) {
				foreach($ev->getTargets() as $session){
					$player = $session->getPlayer();

					if($player === null) continue;
					$pk->commandData = AvailableCommandManager::getInstance()->getCommandData($player);
				}
			}
		}
	}
}
