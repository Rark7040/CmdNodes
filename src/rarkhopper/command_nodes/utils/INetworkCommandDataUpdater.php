<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\player\Player;

interface INetworkCommandDataUpdater{
	/**
	 * @param Player $target {@see AvailableCommandsPacket}によって利用可能なコマンドの情報を更新するプレイヤー
	 */
	public function update(Player $target) : void;

	/**
	 * @internal
	 * @param AvailableCommandsPacket $pk     {@see AvailableCommandsPacket::$commandData}を上書きを実行するパケット
	 * @param ICommandToDataParser    $parser {@see CommandData}を作成するのに私用するパーサー
	 * @param Player                  $target $pkが送信されるプレイヤー
	 */
	public function overwrite(AvailableCommandsPacket $pk, ICommandToDataParser $parser, Player $target) : void;
}
