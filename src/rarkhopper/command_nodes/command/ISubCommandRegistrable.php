<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

interface ISubCommandRegistrable{
	/**
	 * @param SubCommandBase $subCmd このコマンドの1つ目の引数となる文字列を持つサブコマンド
	 * @return $this
	 */
	public function registerSubCommand(SubCommandBase $subCmd) : ISubCommandRegistrable;

	/**
	 * @return array<SubCommandBase> 登録されている全てのサブコマンド
	 */
	public function getSubCommands() : array;
}
