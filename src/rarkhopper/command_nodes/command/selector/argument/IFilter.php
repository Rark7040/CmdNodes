<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use rarkhopper\command_nodes\exception\InvalidExecutorException;

interface IFilter{
	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 * @throws InvalidExecutorException
	 */
	public function filter(Vector3 $vec3, array $entities) : array;
}
