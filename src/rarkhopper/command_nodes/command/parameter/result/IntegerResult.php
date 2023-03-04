<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

class IntegerResult implements IParameterParseResult{
	public function __construct(private int $result){}

	public function asInteger() : int{
		return $this->result;
	}
}
