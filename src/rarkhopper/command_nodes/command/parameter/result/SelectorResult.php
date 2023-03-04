<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

use rarkhopper\command_nodes\command\selector\ISelector;

class SelectorResult implements IParameterParseResult{
	public function __construct(private ISelector $result){}

	public function asSelector() : ISelector{
		return $this->result;
	}
}
