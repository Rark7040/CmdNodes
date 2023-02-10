<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use rarkhopper\command_nodes\command\CommandBase;

interface ICmdNodesCommandMap{
	/**
	 * @param array<CommandBase> $cmds
	 */
	public function registerAll(string $fallbackPrefix, array $cmds) : void;

	public function register(string $fallbackPrefix, CommandBase $cmd) : bool;

	/**
	 * @phpstan-param class-string<CommandBase> $cmdClass
	 */
	public function unregister(string $cmdClass) : bool;

	public function clearCommands() : void;

	/**
	 * @phpstan-param class-string<CommandBase> $cmdClass
	 */
	public function getCommand(string $cmdClass) : ?CommandBase;

	/**
	 * @return array<string, CommandBase>
	 */
	public function getCommands() : array;

	public function needsUpdate() : bool;

	public function unsetUpdateFlags() : void;
}
