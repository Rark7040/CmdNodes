<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

interface ICommandDataUpdater{
	/**
	 * @param array<Command> $cmds
	 */
	public function update(ICommandToDataParser $parser, Player $target, array $cmds) : void;
	/**
	 * @param array<Command> $cmds
	 */
	public function inject(AvailableCommandsPacket $pk, ICommandToDataParser $parser, Player $target, array $cmds) : void;
}
