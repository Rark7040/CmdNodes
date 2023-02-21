<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use RuntimeException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

class FloatParameter extends CommandParameterBase{
	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_FLOAT;
	}

	public function parseArgument(string $rawArg) : float{
		if(filter_var($rawArg, FILTER_VALIDATE_FLOAT) === false) throw new RuntimeException(); //TODO: msg
		return (float) $rawArg;
	}
}
