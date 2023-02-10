<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\params;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

interface ICommandParameter{
	public function getName() : string;
	public function asNetworkParameter() : NetworkParameter;
	public function isOptional() : bool;
	public function getFlags() : int;
	public function validate(string $rawArg) : bool;
}
