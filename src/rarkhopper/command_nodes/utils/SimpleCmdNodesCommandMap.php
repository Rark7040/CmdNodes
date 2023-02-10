<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;

final class SimpleCmdNodesCommandMap implements ICmdNodesCommandMap{
	/** @var array<string, CommandBase> */
	private array $cmds = [];
	private bool $hasUpdate = false;

	public function registerAll(string $fallbackPrefix, array $cmds) : void{
		foreach($cmds as $cmd){
			$this->register($fallbackPrefix, $cmd);
		}
	}

	public function register(string $fallbackPrefix, CommandBase $cmd) : bool{
		$server = Server::getInstance();
		$logger = $server->getLogger();

		if(isset($this->cmds[$cmd::class])){
			$logger->warning('already registered command. ' . $fallbackPrefix . ':' . $cmd->getLabel());
			return false;
		}
		$this->cmds[$cmd::class] = $cmd;
		$logger->debug('registered command. ' . $fallbackPrefix . ':' . $cmd->getLabel());
		$server->getCommandMap()->register($fallbackPrefix, $cmd);
		$this->hasUpdate = true;
		return true;
	}

	public function unregister(string $cmdClass) : bool{
		$server = Server::getInstance();
		$logger = $server->getLogger();
		$cmdMap = $server->getCommandMap();
		$cmd = $this->cmds[$cmdClass] ?? null;

		if($cmd === null){
			$logger->warning('not yet registered ' . $cmdClass . ', but is now trying to unregister.');
			return false;
		}
		$logger->debug('unregistered /' . $cmd->getLabel() . ' command.');
		$cmdMap->unregister($cmd);
		unset($this->cmds[$cmdClass]);
		$this->hasUpdate = true;
		return true;
	}

	public function getCommand(string $cmdClass) : ?CommandBase{
		return $this->cmds[$cmdClass] ?? null;
	}

	public function getCommands() : array{
		return $this->cmds;
	}

	public function clearCommands() : void{
		foreach($this->cmds as $cmd){
			$this->unregister($cmd::class);
		}
		$this->cmds = [];
		$this->hasUpdate = true;
	}

	public function needsUpdate() : bool{
		if($this->hasUpdate) return true;
		foreach($this->cmds as $cmd){
			if(!$cmd->hasUpdate()) continue;
			return true;
		}
		return false;
	}

	public function unsetUpdateFlags() : void{
		$this->hasUpdate = false;

		foreach($this->cmds as $cmd){
			$cmd->setUpdate(false);
		}
	}
}
