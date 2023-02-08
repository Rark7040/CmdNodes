<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandStringHelper;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use rarkhopper\cmdnodes\command\CommandBase;
use rarkhopper\cmdnodes\exception\DuplicateCommandNameException;
use function array_shift;

class CmdNodesCommandMap{
	/** @var array<CommandBase> */
	private array $cmds = [];

	/**
	 * @param array<CommandBase> $cmds
	 */
	public function registerAll(string $fallbackPrefix, array $cmds) : void{
		foreach($cmds as $cmd){
			$this->register($fallbackPrefix, $cmd);
		}
	}

	/**
	 * @throws DuplicateCommandNameException
	 */
	public function register(string $fallbackPrefix, CommandBase $cmd) : void{
		$label = $cmd->getLabel();
		$aliases = $cmd->getAliases();
		$aliases[] = $label;
		$pmCmdMap = Server::getInstance()->getCommandMap();

		foreach($aliases as $name){
			if($pmCmdMap->getCommand($name) !== null){
				throw new DuplicateCommandNameException('already registered command in pmmp`s command map. given ' . $name);
			}
			if(!isset($this->cmds[$name])){
				$this->cmds[$name] = $cmd;
				continue;
			}

			$fallbackName = $fallbackPrefix . ':' . $name;

			if(isset($this->cmds[$fallbackName])){
				throw new DuplicateCommandNameException('already registered command. given ' . $fallbackName);

			}else{
				$this->cmds[$fallbackName] = $cmd;
			}
		}
	}

	public function dispatch(CommandSender $sender, string $cmdLine) : bool{
		$args = CommandStringHelper::parseQuoteAware($cmdLine);
		$label = array_shift($args);

		if($label === null) return true;
		$cmdInstance = $this->getCommand($label);

		if($cmdInstance === null){
			$sender->sendMessage(KnownTranslationFactory::pocketmine_command_notFound($label, "/help")->prefix(TextFormat::RED));
			return true;
		}
		$cmdInstance->timings?->startTiming();

		try{
			$cmdInstance->execute($sender, $label, $args);

		}catch(InvalidCommandSyntaxException $err){
			$sender->sendMessage($sender->getLanguage()->translate(KnownTranslationFactory::commands_generic_usage($cmdInstance->getUsage())));

		}finally{
			$cmdInstance->timings?->stopTiming();
		}
		return false;
	}

	public function clearCommands() : void{
		$this->cmds = [];
	}

	public function getCommand(string $name) : ?CommandBase{
		return $this->cmds[$name] ?? null;
	}

	/**
	 * @return CommandBase[]
	 */
	public function getCommands() : array{
		return $this->cmds;
	}
}
