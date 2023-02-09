<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class SendCommandDataListener implements Listener{
	public function onJoin(PlayerJoinEvent $ev) : void{
		$cmdnodes = CmdNodes::getInstance();
		$cmdnodes->getUpdater()->updateAll(
			$cmdnodes->getParser(),
			$ev->getPlayer(),
			...$cmdnodes->getCommandMap()->getCommands()
		);
	}
}
