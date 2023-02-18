<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use rarkhopper\command_nodes\exception\InvalidFilterOperandException;

interface ISelectorArgument{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function __construct(string $usedType, string $strOperand);

	/**
	 * @return array<string>
	 */
	public static function getTypes() : array;

	public static function isValidOperand(string $strOperand) : bool;

	public function getUsedType() : string;

	public function getRawOperand() : string;

	/**
	 * @return scalar
	 */
	public function getOperand() : mixed;
}
