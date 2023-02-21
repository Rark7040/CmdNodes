<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use InvalidArgumentException;
use pocketmine\command\CommandSender;

final class CommandParameterList implements ICommandParameterList{
	/** @var array<int, ICommandParameter> */
	private array $params = [];

	public function getArguments() : array{
		return $this->params;
	}

	public function registerParameter(ICommandParameter $param, int $offset) : CommandParameterList{
		if($offset < 0) throw new InvalidArgumentException('offset must be greater than 0');
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
