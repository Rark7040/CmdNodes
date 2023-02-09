<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;

interface ICommandToDataParser{
	public function parse(Command $cmd, Player $receiver) : CommandData;
}
