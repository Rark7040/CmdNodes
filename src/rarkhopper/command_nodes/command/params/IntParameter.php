<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use RuntimeException;
use function filter_var;
use const FILTER_VALIDATE_INT;

class IntParameter extends CommandParameterBase{
	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_INT;
	}

	public function parseArgument(string $rawArg) : int{
		if(filter_var($rawArg, FILTER_VALIDATE_INT) !== false) throw new RuntimeException(); //TODO: msg
		return (int) $rawArg;
	}
}
