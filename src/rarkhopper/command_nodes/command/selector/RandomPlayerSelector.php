<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\player\Player;
use pocketmine\Server;
use function count;
use function mt_rand;

final class RandomPlayerSelector extends SelectorBase{
	public static function getIdentifier() : string{
		return 'r';
	}

	public function selectEntities(Player $executor) : array{
		$players = $this->filterEntities($executor, Server::getInstance()->getOnlinePlayers());
		$playersCnt = count($players);

		if($playersCnt < 1) return [];
		$player = $players[mt_rand(0, $playersCnt - 1)] ?? null;

		if($player === null) return [];
		return [$player];
	}
}
