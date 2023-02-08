<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use function substr;

class CmdNodesListener implements Listener{
	public function onReceiveSentCommand(PlayerCommandPreprocessEvent $ev) : void{
		CmdNodes::getInstance()->getCommandMap()->dispatch($ev->getPlayer(), substr($ev->getMessage(), 1));
	}

	public function onLogin(PlayerLoginEvent $ev) : void{
		$player = $ev->getPlayer();

		$data = [];

		foreach(CmdNodes::getInstance()->getCommandMap()->getCommands() as $cmd){
			$data[$cmd->getLabel()] = $cmd->requestCommandData($player);
		}
		$player->getNetworkSession()->sendDataPacket(AvailableCommandsPacket::create($data, [], [], []));
	}
}
