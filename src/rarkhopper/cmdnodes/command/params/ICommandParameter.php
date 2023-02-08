<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\command\params;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

interface ICommandParameter{
	public function getName() : string;
	public function getUsageName() : string;
	public function getNetworkTypeId() : int;
	public function asNetworkParameter() : NetworkParameter;
	public function isOptional() : bool;
	public function getFlags() : int;
}
