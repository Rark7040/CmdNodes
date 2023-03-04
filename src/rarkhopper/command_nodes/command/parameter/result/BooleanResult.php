<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

class BooleanResult implements IParameterParseResult{
	public function __construct(private bool $result){}

	public function asBoolean() : bool{
		return $this->result;
	}
}
