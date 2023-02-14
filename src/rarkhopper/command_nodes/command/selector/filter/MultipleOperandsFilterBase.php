<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\math\Vector3;

abstract class MultipleOperandsFilterBase extends FilterBase implements IMultipleOperandsFilter{

	public function filter(Vector3 $vec3, array $entities) : array{
		//NOOP
		return $entities;
	}
}
