<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

class FloatResult implements IParameterParseResult{
	public function __construct(private float $result){}

	public function asFloat() : float{
		return $this->result;
	}
}
