<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use rarkhopper\command_nodes\exception\InvalidFilterOperandException;

interface IOperandsPool{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function pool(string $key, string $strOperand) : void;
}
