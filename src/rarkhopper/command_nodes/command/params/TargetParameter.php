<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TargetParameter extends CommandParameterBase{

	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}

	public function validate(string $rawArg) : bool{
		return true;
	}
}
