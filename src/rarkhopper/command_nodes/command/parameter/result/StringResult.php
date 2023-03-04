<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

class StringResult implements IParameterParseResult{
	public function __construct(private string $result){}

	public function asString() : string{
		return $this->result;
	}
}
