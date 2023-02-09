<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;

interface ICommandDataUpdater{
	public function update(ICommandToDataParser $parser, Player $target, Command ...$cmds) : void;
	public function inject(AvailableCommandsPacket &$pk, ICommandToDataParser $parser, Player $target, Command ...$cmds) : void;
}
