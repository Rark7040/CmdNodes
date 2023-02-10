<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;
use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;
use function array_values;

/**
 * @internal
 */
final class SimpleNetworkCommandDataUpdater implements INetworkCommandDataUpdater{
	public function update(Player $target) : void{
		$target->getNetworkSession()->syncAvailableCommands();
	}

	public function overwrite(AvailableCommandsPacket $pk, ICommandToNetworkDataParser $parser, Player $target) : void{
		$pk->commandData = $this->createCommandData($parser, $target);
	}

	/**
	 * @return array<CommandData>
	 */
	private function createCommandData(ICommandToNetworkDataParser $parser, Player $target) : array{
		$cmdDataPool = [];
		$logger = Server::getInstance()->getLogger();

		foreach(Server::getInstance()->getCommandMap()->getCommands() as $cmd){
			if(!$cmd->testPermissionSilent($target)) continue;
			$cmdDataPool[$cmd::class] = $parser->parse($cmd, $target);

			if(!$cmd instanceof CommandBase) continue;
			$logger->debug('updated ' . $cmd->getLabel() . ' command data');
		}
		return array_values($cmdDataPool);
	}
}
