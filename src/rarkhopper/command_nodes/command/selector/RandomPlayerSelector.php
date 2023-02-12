<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use function array_values;
use function count;
use function mt_rand;

final class RandomPlayerSelector extends BaseSelector{
	public static function getIdentifier() : string{
		return 'r';
	}

	public function select(?CommandSender $executor) : array{
		$players = array_values(Server::getInstance()->getOnlinePlayers());
		$playersCnt = count($players);

		if($playersCnt < 1) return [];
		$player = $players[mt_rand(0, $playersCnt - 1)] ?? null;

		if($player === null) return [];
		return [$player];
	}
}
