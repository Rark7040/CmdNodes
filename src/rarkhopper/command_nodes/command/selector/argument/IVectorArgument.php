<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\math\Vector3;
use rarkhopper\command_nodes\command\selector\IOperandsPool;

interface IVectorArgument extends IPoolableArgument{
	public function getVector3(Vector3 $vec3, IOperandsPool $pool) : Vector3;
}
