<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

interface IStringToFilterParser{

	/**
	 * @param array<string>         $types
	 * @param class-string<IFilter> $filterClass 登録をするフィルターの文字列クラス
	 */
	public function register(array $types, string $filterClass, bool $override = false) : IStringToFilterParser;

	public function getFilter(string $strFilter) : ?IFilter;
}
