<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes\utils;

use pocketmine\command\Command;
use pocketmine\player\Player;

interface ICommandDataUpdater{
	public function update(ICommandToDataParser $parser, Command $cmd, Player $target) : void;

	public function updateAll(ICommandToDataParser $parser, Player $target, Command ...$cmds) : void;
}
