<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;
use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;
use function array_merge;

final class SimpleCommandDataUpdater implements ICommandDataUpdater{
	public function update(ICommandToDataParser $parser, Player $target, Command ...$cmds) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create($this->createCommandData(
				$parser,
				$target,
				...array_merge(self::getDefaultCommands(), $cmds)
			), [], [], [])
		);
	}

	public function inject(AvailableCommandsPacket &$pk, ICommandToDataParser $parser, Player $target, Command ...$cmds) : void{
		$pk->commandData = $this->createCommandData($parser, $target, ...$cmds);
	}

	/**
	 * @return array<CommandData>
	 */
	private function createCommandData(ICommandToDataParser $parser, Player $target, Command ...$cmds) : array{
		$cmdDataPool = [];
		$logger = Server::getInstance()->getLogger();

		foreach(array_merge(self::getDefaultCommands(), $cmds) as $cmd){
			if(!$cmd->testPermissionSilent($target)) continue;
			$cmdDataPool[$cmd->getLabel()] = $parser->parse($cmd, $target); //fix duplicate command by array key

			if(!$cmd instanceof CommandBase) continue;
			$logger->debug('updated ' . $cmd->getLabel() . ' command data');
		}
		return array_values($cmdDataPool);
	}

	/**
	 * @return array<Command>
	 */
	private static function getDefaultCommands() : array{
		return Server::getInstance()->getCommandMap()->getCommands();
	}
}
