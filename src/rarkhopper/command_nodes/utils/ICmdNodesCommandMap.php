<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use rarkhopper\command_nodes\command\CommandBase;

interface ICmdNodesCommandMap{
	/**
	 * @param array<CommandBase> $cmds
	 */
	public function registerAll(string $fallbackPrefix, array $cmds) : void;

	public function register(string $fallbackPrefix, CommandBase $cmd) : bool;

	/**
	 * @param array<string> $args
	 */
	public function dispatch(CommandSender $sender, string $label, array $args) : bool;

	public function unregister(CommandBase $cmd) : bool;

	public function clearCommands() : void;

	public function getCommand(string $label) : ?Command;

	/**
	 * @return array<string, CommandBase>
	 */
	public function getCommands() : array;

	/**
	 * @return array<string, CommandBase>
	 */
	public function getHasUpdateCommands() : array;
}
