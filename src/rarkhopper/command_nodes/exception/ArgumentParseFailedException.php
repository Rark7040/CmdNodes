<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\exception;

use rarkhopper\command_nodes\command\parameter\ICommandParameter;

class ArgumentParseFailedException extends CommandNodesException{
	public function __construct(
		private ICommandParameter $param,
		private ?string $rawArg
	){
		parent::__construct('invalid argument ' . $param->getLabel() . ':' . ($rawArg ?? 'null'));
	}

	public function getParameter() : ICommandParameter{
		return $this->param;
	}

	public function getRawArgument() : ?string{
		return $this->rawArg;
	}
}
