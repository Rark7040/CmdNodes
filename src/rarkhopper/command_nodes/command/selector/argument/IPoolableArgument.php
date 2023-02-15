<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use rarkhopper\command_nodes\command\selector\IOperandsPool;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;

interface IPoolableArgument{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function pool(IOperandsPool $pool) : void;
}
