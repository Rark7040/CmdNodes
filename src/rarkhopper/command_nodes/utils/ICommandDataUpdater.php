<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

interface ICommandDataUpdater{
	public function update(Player $target) : void;
	/**
	 * @internal
	 */
	public function overwrite(AvailableCommandsPacket $pk, ICommandToDataParser $parser, Player $target) : void;
}
