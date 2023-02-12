<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;

final class AllPlayersSelector extends BaseSelector{
	public static function getIdentifier() : string{
		return 'a';
	}

	public function select(?CommandSender $executor) : array{
		return Server::getInstance()->getOnlinePlayers();
	}
}
