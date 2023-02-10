<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;

interface ICmdNodesCommandMap{

	/**
	 * @param string             $fallbackPrefix {@see CommandBase::$label}が重複したときにラベルの先頭に付与される文字列
	 * @param array<CommandBase> $cmds           {@see ICmdNodesCommandMap::register()}によって登録を実行するコマンド
	 */
	public function registerAll(string $fallbackPrefix, array $cmds) : void;

	/**
	 * @param string      $fallbackPrefix {@see CommandBase::$label}が重複したときにラベルの先頭に付与される文字列
	 * @param CommandBase $cmd            登録を実行するコマンドであり、{@see Server::$commandMap}に格納されているコマンドマップにも保存されます
	 */
	public function register(string $fallbackPrefix, CommandBase $cmd) : bool;

	/**
	 * @phpstan-param class-string<CommandBase> $cmdClass 登録解除をするコマンドの文字列クラス
	 */
	public function unregister(string $cmdClass) : bool;

	/**
	 * 登録されている全てのコマンドを登録解除する
	 */
	public function clearCommands() : void;

	/**
	 * @phpstan-param class-string<CommandBase> $cmdClass 取得したいコマンドの文字列クラス
	 */
	public function getCommand(string $cmdClass) : ?CommandBase;

	/**
	 * @return array<string, CommandBase> 登録されている全てのコマンドを格納した配列
	 */
	public function getCommands() : array;
}
