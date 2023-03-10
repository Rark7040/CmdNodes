<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\player\Player;
use pocketmine\Server;

final class AllPlayersSelector extends SelectorBase{
	public static function getIdentifier() : string{
		return 'a';
	}

	public function selectEntities(Player $executor) : array{
		return $this->filterEntities($executor, Server::getInstance()->getOnlinePlayers());
	}
}
