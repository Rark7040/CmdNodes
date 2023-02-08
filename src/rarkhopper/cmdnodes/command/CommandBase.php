<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use function array_values;
use function count;
use function in_array;
use function strtolower;
use function ucfirst;

abstract class CommandBase extends Command implements IPermissionTestable, PluginOwned{
	/** @var array<SubCommandBase> */
	private array $subCmds = [];

	protected function registerSubCommand(SubCommandBase $subCmd) : CommandBase{
		$this->subCmds[] = $subCmd;
		return $this;
	}

	/**
	 * @param array<string> $args
	 * @throws CommandException
	 */
	abstract protected function onRun(CommandSender $sender, string $usedAlias, array $args) : void;

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$this->onRun($sender, $commandLabel, $args);
	}

	public function requestCommandData(CommandSender $receiver) : CommandData{
		return new CommandData(
			$this->getLabel(),
			$this->getStringDescription(),
			0,
			0,
			$this->getEnum(),
			$this->getOverloads($receiver)
		);
	}

	private function getStringDescription() : string{
		$description = $this->getDescription();

		if($description instanceof Translatable){
			$lang = Server::getInstance()->getLanguage();
			$description = $lang->translate($description);
		}
		return $description;
	}

	private function getEnum() : ?CommandEnum{
		$aliases = $this->getAliases();

		if(count($aliases) < 1) return null;
		$label = strtolower($this->getLabel());

		if(!in_array($label, $aliases, true)){
			$aliases[] = $label;
		}
		return new CommandEnum(
			ucfirst($this->getLabel()) . 'Aliases',
			array_values($aliases)
		);
	}

	/**
	 * @return CommandParameter[][]
	 */
	private function getOverloads(CommandSender $receiver) : array{
		$overloads = [];

		foreach ($this->subCmds as $subCmd){
			if(!$subCmd->testPermission($receiver)) continue;
			$overloads[] = $subCmd->getParameters();
		}
		return $overloads;
	}
}
