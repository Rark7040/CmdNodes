<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;
use rarkhopper\cmdnodes\command\CommandBase;

class CommandDataCache {

	private Command $command;
	private CommandData $commandData;

	public function __construct(Command $command, CommandData $commandData) {
		$this->command = $command;
		$this->commandData = $commandData;
	}

	public function getCommandData(Player $player) : CommandData {
		if ($this->command instanceof CommandBase) {
			return $this->command->requestCommandData($player);
		}
		return $this->commandData;
	}

	public function getCommand() : Command {
		return $this->command;
	}
}