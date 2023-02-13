<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

interface IStringToValidatorParser{

	/**
	 * @param array<string>            $types
	 * @param class-string<IValidator> $validatorClass 登録をするバリデーターの文字列クラス
	 */
	public function register(array $types, string $validatorClass, bool $override = false) : IStringToValidatorParser;

	public function getValidator(string $strValidator) : ?IValidator;
}
