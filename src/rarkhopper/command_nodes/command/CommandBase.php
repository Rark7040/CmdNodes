<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

/**
 * @internal
 */
abstract class CommandBase extends Command implements IExecutable{

	/**
	 * @internal
	 * @return array<array<NetworkParameter>>
	 */
	abstract public function getOverloads(CommandSender $receiver) : array;

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$this->prepareExec($sender, $args);
	}
}
