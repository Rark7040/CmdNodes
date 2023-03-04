<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\parameter;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\argument\ICommandArgument;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;

interface ICommandParameter extends ICommandArgument{
	/**
	 * @internal
	 */
	public function asNetworkParameter() : NetworkParameter;
	public function isOptional() : bool;
	public function getFlags() : int;
	//TODO: failed message
	public function parseArgument(string $rawArg) : IParameterParseResult;
	public function getSpan() : int;
}
