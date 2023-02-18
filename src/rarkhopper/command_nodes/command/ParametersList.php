<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\params\ICommandParameter;

final class ParametersList implements IOverloadsList{
	/** @var array<int, ICommandParameter> */
	private array $params = [];

	/**
	 * @return $this
	 */
	public function registerParameter(ICommandParameter $param, int $pos) : ParametersList{ //TODO: validate pos
		$this->params[$pos] = $param;
		return $this;
	}

	/**
	 * @internal
	 * @return NetworkParameter[][]
	 */
	public function asNetworkParameters(CommandSender $receiver) : array{
		$overloads = [[]];

		foreach($this->params as $param){
			$overloads[][] = $param->asNetworkParameter();
		}
		return $overloads;
	}
}
