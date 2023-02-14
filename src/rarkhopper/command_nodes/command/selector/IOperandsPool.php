<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use rarkhopper\command_nodes\exception\InvalidFilterOperandException;

interface IOperandsPool{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function pool(string $key, string $strOperand) : void;
	public function getInt(string $key) : ?int;
	public function getFloat(string $key) : ?float;
	public function getString(string $key) : ?string;
	public function getBool(string $key) : ?bool;
}
