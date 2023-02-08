<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class SubCommandBase {
	/**
	 * @param array<string> $args
	 */
	abstract protected function onRun(CommandSender $sender, string $usedAlias, array $args) : void;

	/**
	 * @return array<CommandParameter>
	 */
	public function getParameters() : array{
		return [];
	}
}
