<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

interface IStringToValidatorParser{
	/**
	 * @param class-string<IValidator> $validatorClass 登録をするバリデーターの文字列クラス
	 */
	public function register(string $type, string $validatorClass, bool $override = false) : IStringToValidatorParser;
}
