<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use rarkhopper\command_nodes\command\parameter\result\FloatResult;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;
use RuntimeException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

class FloatParameter extends CommandParameterBase{
	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_FLOAT;
	}

	public function parseArgument(string $rawArg) : IParameterParseResult{
		if(filter_var($rawArg, FILTER_VALIDATE_FLOAT) === false) throw new RuntimeException(); //TODO: msg
		return new FloatResult((float) $rawArg);
	}
}
