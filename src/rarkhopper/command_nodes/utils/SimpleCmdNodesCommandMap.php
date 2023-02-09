<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;

final class SimpleCmdNodesCommandMap implements ICmdNodesCommandMap{
	/** @var array<string, CommandBase> */
	private array $cmds = [];

	public function registerAll(string $fallbackPrefix, array $cmds) : void{
		foreach($cmds as $cmd){
			$this->register($fallbackPrefix, $cmd);
		}
	}

	public function register(string $fallbackPrefix, CommandBase $cmd) : bool{
		$server = Server::getInstance();
		$logger = $server->getLogger();
		$registered = $server->getCommandMap()->register($fallbackPrefix, $cmd);

		if($registered){
			$this->cmds[$cmd->getLabel()] = $cmd;
			$logger->debug('registered command. ' . $fallbackPrefix . ':' . $cmd->getLabel());

		}else{
			$logger->warning('already registered command. ' . $fallbackPrefix . ':' . $cmd->getLabel());
		}
		$this->cmds[$cmd->getLabel()] = $cmd;

		return $registered;
	}

	public function unregister(CommandBase $cmd) : bool{
		$label = $cmd->getLabel();
		$server = Server::getInstance();
		$logger = $server->getLogger();
		$cmdMap = $server->getCommandMap();
		unset($this->cmds[$label]);
		$isRegistered = $cmdMap->getCommand($label) !== null;

		if($isRegistered){
			$logger->debug('unregistered /' . $label . ' command.');
			$cmdMap->unregister($cmd);

		}else{
			$logger->warning('not yet registered /' . $label . ' command, but is now unregistered.');
		}
		return $isRegistered;
	}

	public function getCommand(string $label) : ?CommandBase{
		return $this->cmds[$label] ?? null;
	}

	public function getCommands() : array{
		return $this->cmds;
	}

	/**
	 * @param array<string> $args
	 */
	public function dispatch(CommandSender $sender, string $label, array $args) : bool{
		$cmd = $this->getCommand($label);

		if($cmd === null) return false;
		$cmd->execute($sender, $label, $args);
		return true;
	}

	public function clearCommands() : void{
		foreach($this->cmds as $cmd){
			$this->unregister($cmd);
		}
		$this->cmds = [];
	}
}