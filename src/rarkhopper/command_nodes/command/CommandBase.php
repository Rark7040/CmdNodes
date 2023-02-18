<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function array_shift;
use function strtolower;

abstract class CommandBase extends Command implements IExecutable{
	private ?IOverloadsList $overloads = null;

	abstract public function onRun(CommandSender $sender, array $args) : void;

	public function setOverloads(?IOverloadsList $overloads) : void{
		$this->overloads = $overloads;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$subCmdLabel = array_shift($args);

		if($subCmdLabel === null){
			$this->onRun($sender, $args);
			return;
		}
		$subCmd = $this->subCmds[strtolower((string) $subCmdLabel)] ?? null;

		if($subCmd === null){
			throw new InvalidCommandSyntaxException();
		}
		$subCmd->onRun($sender, $args);
	}

	/**
	 * @internal
	 * @return NetworkParameter[][]
	 */
	final public function getOverloads(CommandSender $receiver) : array{
		return $this->overloads === null? []: $this->overloads->asNetworkParameters($receiver);
	}
}
