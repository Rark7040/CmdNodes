<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\Server;
use function array_values;
use function ucfirst;

abstract class CommandBase extends Command {
	/** @var array<SubCommandBase> */
	private array $subCmds = [];

	protected function registerSubCommand(SubCommandBase $subCmd) : void{
		$this->subCmds[] = $subCmd;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$this->onRun($sender, $commandLabel, $args);
	}

	/**
	 * @param array<string> $args
	 */
	abstract protected function onRun(CommandSender $sender, string $usedAlias, array $args) : void;

	public function requestCommandData(CommandSender $receiver) : CommandData {
		return new CommandData(
			$this->getName(),
			$this->getStringDescription(),
			0,
			0,
			$this->getEnum(),
			$this->getOverloads($receiver)
		);
	}

	private function getStringDescription() : string {
		$description = $this->getDescription();

		if($description instanceof Translatable){
			$lang = Server::getInstance()->getLanguage();
			$description = $lang->translate($description);
		}
		return $description;
	}

	private function getEnum() : CommandEnum{
		return new CommandEnum(
			ucfirst($this->getName()) . 'Aliases',
			array_values($this->getAliases())
		);
	}

	/**
	 * @return CommandParameter[][]
	 */
	private function getOverloads(CommandSender $receiver) : array{
		$overloads = [];

		foreach ($this->subCmds as $subCmd){
			if(!$this->testPermissionSilent($receiver)) continue;
			$overloads[] = $subCmd->getParameters();
		}
		return $overloads;
	}
}
