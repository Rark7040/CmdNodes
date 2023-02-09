<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use rarkhopper\command_nodes\command\CommandBase;

interface ICmdNodesCommandMap{
	/**
	 * @param array<CommandBase> $cmds
	 */
	public function registerAll(string $fallbackPrefix, array $cmds) : void;

	public function register(string $fallbackPrefix, CommandBase $cmd) : bool;

	public function unregister(CommandBase $cmd) : bool;

	public function clearCommands() : void;

	/**
	 * @phpstan-param class-string<Command> $commandClass
	 */
	public function getCommand(string $commandClass) : ?Command;

	/**
	 * @return array<string, CommandBase>
	 */
	public function getCommands() : array;

	/**
	 * @return array<string, CommandBase>
	 */
	public function getHasUpdateCommands() : array;
}
