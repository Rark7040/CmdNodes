<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

interface IOverloadsList{
	/**
	 * @return array<array<NetworkParameter>>
	 */
	public function asNetworkParameters(CommandSender $receiver) : array;
}
