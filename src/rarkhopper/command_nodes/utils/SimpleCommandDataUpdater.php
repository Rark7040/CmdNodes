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
use function array_values;

final class SimpleCommandDataUpdater implements ICommandDataUpdater{
	public function update(ICommandToDataParser $parser, Player $target) : void{
		$target->getNetworkSession()->sendDataPacket(
			AvailableCommandsPacket::create($this->createCommandData($parser, $target, Server::getInstance()->getCommandMap()->getCommands()), [], [], [])
		);
	}

	public function overwrite(AvailableCommandsPacket $pk, ICommandToDataParser $parser, Player $target) : void{
		$pkData = $pk->commandData;
		$newData = $this->createCommandData($parser, $target, Server::getInstance()->getCommandMap()->getCommands());
		$pk->commandData = array_merge($pkData, $newData);
	}

	/**
	 * @param array<Command> $cmds
	 * @return array<CommandData>
	 */
	private function createCommandData(ICommandToDataParser $parser, Player $target, array $cmds) : array{
		$cmdDataPool = [];
		$logger = Server::getInstance()->getLogger();

		foreach($cmds as $cmd){
			if(!$cmd->testPermissionSilent($target)) continue;
			$cmdDataPool[] = $parser->parse($cmd, $target);

			if(!$cmd instanceof CommandBase) continue;
			$logger->debug('updated ' . $cmd->getLabel() . ' command data');
		}
		return array_values($cmdDataPool);
	}
}
