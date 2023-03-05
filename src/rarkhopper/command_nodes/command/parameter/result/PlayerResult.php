<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter\result;

use pocketmine\player\Player;

class PlayerResult implements IParameterParseResult{
	public function __construct(private ?Player $result){}

	public function asPlayer() : ?Player{
		return $this->result;
	}
}
