<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use function array_shift;
use function strtolower;

/**
 * @internal
 */
abstract class CommandBase extends Command implements ICommandNodesCommand{
	/** @var array<string, SubCommandBase> */
	private array $subCmds = [];

	abstract public function onRun(CommandSender $sender, array $args) : void;

	/**
	 * @internal
	 * @return CommandParameter[][]
	 */
	abstract public function getOverloads(CommandSender $receiver) : array;

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
}
