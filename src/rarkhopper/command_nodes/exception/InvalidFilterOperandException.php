<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\exception;

use Throwable;

class InvalidFilterOperandException extends CommandNodesException{
	public function __construct(
		private string $invalidOperand,
		int $code = 0,
		?Throwable $previous = null
	){
		parent::__construct('invalid operand ' . $invalidOperand, $code, $previous);
	}

	public function getInvalidOperand() : string{
		return $this->invalidOperand;
	}
}
