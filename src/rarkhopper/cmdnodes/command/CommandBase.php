<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class CommandBase extends Command implements IPermissionTestable{
	/** @var array<SubCommandBase> */
	private array $subCmds = [];

	protected function registerSubCommand(SubCommandBase $subCmd) : CommandBase{
		$this->subCmds[] = $subCmd;
		return $this;
	}

	/**
	 * @param array<string> $args
	 * @throws CommandException
	 */
	abstract protected function onRun(CommandSender $sender, string $usedAlias, array $args) : void;

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$this->onRun($sender, $commandLabel, $args);
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
