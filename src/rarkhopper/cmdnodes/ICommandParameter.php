<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

interface ICommandParameter {
	public function asNetworkParameter() : NetworkParameter;
}