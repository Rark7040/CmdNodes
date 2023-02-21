<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;

interface IExecutable extends IPermissionTestable{
	/**
	 * コマンドが実行されたときに呼び出される関数
	 *
	 * @param CommandSender                        $sender     コマンドを実行したプレイヤー
	 * @param array<string>                        $rawArgs    コマンドライン引数
	 * @param array<string, IParameterParseResult> $parsedArgs パラメータによって型バリデーションされた引数
	 * @throws CommandException 内部で不整合により処理を中断する場合にはこの例外を投げてください
	 */
	public function exec(CommandSender $sender, array $rawArgs, array $parsedArgs) : void;

	/**
	 * @internal
	 * @param CommandSender      $sender コマンドを実行したプレイヤー
	 * @param array<int, string> $args   コマンドライン引数
	 * @throws CommandException 内部で不整合により処理を中断する場合にはこの例外を投げてください
	 */
	public function prepareExec(CommandSender $sender, array $args) : void;

	public function getLabel() : string;
}
