<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;

abstract class ParameterRegistrableCommand extends CommandBase implements IParameterRegistrable{
	use ParameterRegistrableTrait;

	final public function getOverloads(CommandSender $receiver) : array{
		$overloads = [[]];

		foreach($this->params as $param){
			$overloads[][] = $param->asNetworkParameter();
		}
		return $overloads;
	}
}