<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Server;
use rarkhopper\cmdnodes\command\CommandBase;

class SendCommandDataListener implements Listener{
	public function onJoin(PlayerJoinEvent $ev) : void{
		$player = $ev->getPlayer();
		$parser = CommandToDataParser::getInstance();
		$data = [];

		foreach(Server::getInstance()->getCommandMap()->getCommands() as $cmd){
			if(!$cmd instanceof CommandBase) continue;
			$data[] = $parser->parse($cmd, $player);
		}
		$player->getNetworkSession()->sendDataPacket(AvailableCommandsPacket::create($data, [], [], []));
	}
}
