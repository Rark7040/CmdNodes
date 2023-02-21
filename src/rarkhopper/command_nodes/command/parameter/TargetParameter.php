<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use rarkhopper\command_nodes\command\selector\ISelector;
use rarkhopper\command_nodes\CommandNodes;
use RuntimeException;

class TargetParameter extends CommandParameterBase{
	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}

	public function parseArgument(string $rawArg) : ISelector{
		$nodes = CommandNodes::getInstance();

		if($nodes === null) throw new RuntimeException(); //TODO: msg
		$selector = $nodes->getSelectorParser()->parse($rawArg);

		if($selector === null) throw new RuntimeException(); //TODO: msg
		return $selector;
	}
}
