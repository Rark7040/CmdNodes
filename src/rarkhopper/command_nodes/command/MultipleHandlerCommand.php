<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use rarkhopper\command_nodes\command\argument\SubCommandBase;
use RuntimeException;
use function array_shift;
use function gettype;
use function is_string;

abstract class MultipleHandlerCommand extends CommandBase{
	/** @var array<string, SubCommandBase> */
	private array $subCmds = [];

	public function registerSubCommand(SubCommandBase $subCmd) : void{
		$this->subCmds[$subCmd->getLabel()] = $subCmd;
	}

	/**
	 * @return array<SubCommandBase>
	 */
	public function getSubCommands() : array{
		return $this->subCmds;
	}

	final public function prepareExec(CommandSender $sender, array $args) : void{
		if(!$this->testPermission($sender)) return;
		$headArg = array_shift($args);

		if($headArg === null){
			$this->exec($sender, $args, []);
			return;
		}
		if(!is_string($headArg)) throw new RuntimeException('arguments must be a string, but got ' . gettype($headArg));
		if(!isset($this->subCmds[$headArg])) throw new InvalidCommandSyntaxException();
		$this->subCmds[$headArg]->prepareExec($sender, $args);
	}

	final public function getOverloads(CommandSender $receiver) : array{
		$overload = [];

		foreach($this->subCmds as $subCmd){
			$overload[] = $subCmd->asNetworkParameters($receiver);
		}
		return $overload;
	}
}
