<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;

interface ICommandToDataParser{
	/**
	 * @internal
	 * @param Command $cmd      {@see CommandData}を作成するコマンド
	 * @param Player  $receiver {@see AvailableCommandsPacket}を受け取るプレイヤー
	 */
	public function parse(Command $cmd, Player $receiver) : CommandData;
}
