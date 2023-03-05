<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\player\Player;

final class SelfSelector extends SelectorBase{
	public static function getIdentifier() : string{
		return 's';
	}

	public function selectEntities(Player $executor) : array{
		return $this->filterEntities($executor, [$executor]);
	}
}
