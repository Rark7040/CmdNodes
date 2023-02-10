<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\params;

use function filter_var;
use const FILTER_VALIDATE_BOOLEAN;

class BoolParameter extends EnumParameter{
	public array $enums = ['false', 'true'];

	public function validate(string $rawArg) : bool{
		return filter_var($rawArg, FILTER_VALIDATE_BOOLEAN) !== false;
	}
}
