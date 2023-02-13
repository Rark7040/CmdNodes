<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\player\Player;

abstract class MultipleOperandsFilter extends FilterBase implements IMultipleOperandsFilter{

	public function filter(Player $executor, array $entities) : array{
		//NOOP
		return $entities;
	}

	public function pool(string $key, IOperandsPool $pool) : void{
		$pool->pool($key, $this->getOperand());
	}
}
