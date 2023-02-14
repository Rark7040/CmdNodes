<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\IOperandsPool;
use rarkhopper\command_nodes\exception\InvalidExecutorException;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;

interface IMultipleOperandsFilter{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function pool(IOperandsPool $pool) : void;

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 * @throws InvalidExecutorException
	 */
	public function filterOnCompletion(Player $executor, array $entities, IOperandsPool $pool) : array;
}
