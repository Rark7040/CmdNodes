<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\argument;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

interface ICommandArgument{
	public function getLabel() : string;
	public function asNetworkParameter() : NetworkParameter;
}
