<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

abstract class CommandBase extends Command implements IExecutable, IArgumentHoldable{
	/** @var array<int, ICommandArgumentList> */
	private array $argsLists = [];

	public function appendArgumentList(ICommandArgumentList $args) : void{
		$this->argsLists[] = $args;
	}

	public function getArgumentLists() : array{
		return $this->argsLists;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;

	}

	/**
	 * @internal
	 * @return NetworkParameter[][]
	 */
	final public function getOverloads(CommandSender $receiver) : array{
		$overloads = [];

		foreach($this->argsLists as $ls){
			$overloads[] = $ls->asNetworkParameters($receiver);
		}
		return $overloads;
	}
}
