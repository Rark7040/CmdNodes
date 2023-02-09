<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use function array_shift;
use function strtolower;

abstract class CommandBase extends Command implements IPermissionTestable{
	/** @var array<string, SubCommandBase> */
	private array $subCmds = [];

	/**
	 * @param array<string> $args
	 * @throws CommandException
	 */
	abstract protected function onRun(CommandSender $sender, array $args) : void;

	protected function registerSubCommand(SubCommandBase $subCmd) : CommandBase{
		$this->subCmds[strtolower($subCmd->getLabel())] = $subCmd;
		return $this;
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
	 * @return CommandParameter[][]
	 */
	public function getOverloads(CommandSender $receiver) : array{
		$overloads = [];

		foreach ($this->subCmds as $subCmd){
			if(!$subCmd->testPermission($receiver)) continue;
			$overloads[] = $subCmd->getParameters();
		}
		return $overloads;
	}
}
