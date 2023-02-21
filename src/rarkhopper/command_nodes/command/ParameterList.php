<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use rarkhopper\command_nodes\command\params\ICommandParameter;

final class ParameterList implements ICommandArgumentList{
	/** @var array<int, ICommandParameter> */
	private array $params = [];

	public function registerParameter(ICommandParameter $param, int $offset) : ParameterList{ //TODO: validate pos
		$this->params[$offset] = $param;
		return $this;
	}

	public function asNetworkParameters(CommandSender $receiver) : array{
		$overloads = [];

		foreach($this->params as $offset => $param){
			$overloads[$offset] = $param->asNetworkParameter();
		}
		return $overloads;
	}
}
