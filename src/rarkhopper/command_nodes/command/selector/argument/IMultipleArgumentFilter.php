<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use rarkhopper\command_nodes\command\selector\IOperandsPool;

interface IMultipleArgumentFilter extends IPoolableArgument{
	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 */
	public function filterOnCompletion(Vector3 $vec3, array $entities, IOperandsPool $pool) : array;
}
