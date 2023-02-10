<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use function strtolower;

abstract class SubCommandRegistrableCommand extends CommandBase implements ISubCommandRegistrable{
	/** @var array<string, SubCommandBase> */
	private array $subCmds = [];

	public function registerSubCommand(SubCommandBase $subCmd) : self{
		$this->subCmds[strtolower($subCmd->getLabel())] = $subCmd;
		return $this;
	}

	public function getSubCommands() : array{
		return $this->subCmds;
	}

	final public function getOverloads(CommandSender $receiver) : array{
		$overloads = [];

		foreach($this->subCmds as $subCmd){
			if(!$subCmd->testPermission($receiver)) continue;
			$overloads[] = $subCmd->getNetworkParameters();
		}
		return $overloads;
	}
}
