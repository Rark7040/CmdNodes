<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\parameter;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\argument\ICommandArgument;

interface ICommandParameter extends ICommandArgument{
	/**
	 * @internal
	 */
	public function asNetworkParameter() : NetworkParameter;
	public function isOptional() : bool;
	public function getFlags() : int;
	//TODO: failed message
	public function parseArgument(string $rawArg) : mixed;
	public function getSpan() : int;
}
