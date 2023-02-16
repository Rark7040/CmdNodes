<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

interface IFilter{
	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 */
	public function filter(Vector3 $vec3, array $entities) : array;
}
