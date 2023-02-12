<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

interface IStringToSelectorParser{
	/**
	 * @param class-string<ISelector> $selectorClass 登録をするセレクターの文字列クラス
	 */
	public function register(string $id, string $selectorClass) : IStringToSelectorParser;

	public function parse(string $arg) : ?ISelector;
}
