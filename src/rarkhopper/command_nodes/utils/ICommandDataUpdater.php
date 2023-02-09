<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

interface ICommandDataUpdater{
	public function update(Player $target) : void;
	/**
	 * @internal
	 * @param array<Command> $cmds
	 */
	public function overwrite(AvailableCommandsPacket $pk, ICommandToDataParser $parser, Player $target, array $cmds) : void;
}
