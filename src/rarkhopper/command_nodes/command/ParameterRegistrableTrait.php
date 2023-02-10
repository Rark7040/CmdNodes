<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use rarkhopper\command_nodes\command\params\ICommandParameter;

trait ParameterRegistrableTrait{
	/** @var array<ICommandParameter> */
	private array $params = [];

	public function registerParameter(ICommandParameter $param, int $position) : self{
		$this->params[$position] = $param;
		return $this;
	}

	public function getParameters() : array{
		return $this->params;
	}
}
