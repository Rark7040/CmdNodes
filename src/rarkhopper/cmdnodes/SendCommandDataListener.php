<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class SendCommandDataListener implements Listener{
	public function onJoin(PlayerJoinEvent $ev) : void{
		$cmdnodes = CmdNodes::getInstance();
		$player = $ev->getPlayer();
		$parser = $cmdnodes->getParser();
		$data = [];

		foreach($cmdnodes->getCommandMap()->getCommands() as $cmd){
			$data[] = $parser->parse($cmd, $player);
		}
		$player->getNetworkSession()->sendDataPacket(AvailableCommandsPacket::create($data, [], [], []));
	}
}
