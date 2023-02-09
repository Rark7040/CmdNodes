<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes\utils;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use rarkhopper\cmdnodes\command\CommandBase;

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
		$registered = $server->getCommandMap()->register($fallbackPrefix, $cmd);

		if($registered){
			$this->cmds[$cmd->getLabel()] = $cmd;

		}else{
			$server->getLogger()->warning('already registered command. given ' . $fallbackPrefix . ':' . $cmd->getLabel());
		}
		$this->cmds[$cmd->getLabel()] = $cmd;

		return $registered;
	}

	public function unregister(CommandBase $cmd) : bool{
		$label = $cmd->getLabel();
		$server = Server::getInstance();
		$logger = $server->getLogger();
		unset($this->cmds[$label]);

		$unregistered = $server->getCommandMap()->unregister($cmd);

		if($unregistered){
			$logger->debug('unregistered /' . $label . 'command. ');

		}else{
			$logger->warning('not yet registered /' . $label . 'command, but is now unregistered');
		}
		return $unregistered;
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
