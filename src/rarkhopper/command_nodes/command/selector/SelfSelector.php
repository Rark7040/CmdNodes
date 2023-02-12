<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class SelfSelector extends BaseSelector{
	public static function getIdentifier() : string{
		return 's';
	}

	public function select(?CommandSender $executor) : array{
		return $executor instanceof Player? [$executor]: [];
	}
}
