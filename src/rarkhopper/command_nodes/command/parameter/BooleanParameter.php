<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use rarkhopper\command_nodes\command\parameter\result\BooleanResult;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;
use RuntimeException;
use function filter_var;
use function is_bool;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;

class BooleanParameter extends EnumParameter{
	public array $enums = ['true', 'false'];

	public function parseArgument(string $rawArg) : IParameterParseResult{
		$arg = filter_var($rawArg, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

		if(!is_bool($arg)) throw new RuntimeException(); //TODO: msg
		return new BooleanResult($arg);
	}
}
