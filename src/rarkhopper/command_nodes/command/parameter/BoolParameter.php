<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use RuntimeException;
use function filter_var;
use const FILTER_VALIDATE_BOOLEAN;

class BoolParameter extends EnumParameter{
	public array $enums = ['true', 'false'];

	public function parseArgument(string $rawArg) : bool{
		if(filter_var($rawArg, FILTER_VALIDATE_BOOLEAN) === false) throw new RuntimeException(); //TODO: msg
		return (bool) $rawArg;
	}
}
