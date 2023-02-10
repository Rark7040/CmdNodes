<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use rarkhopper\command_nodes\command\params\ICommandParameter;

interface IParameterRegistrable{
	/**
	 * 引数となる値を位置を指定して追加します
	 *
	 * @param ICommandParameter $param    引数となる値
	 * @param int               $position 引数の位置 barがこのサブコマンドのラベルで、0にbazを指定した場合/foo bar bazのようなシンタックスになる
	 * @return $this
	 */
	public function registerParameter(ICommandParameter $param, int $position) : IParameterRegistrable;

	/**
	 * @return array<ICommandParameter> 格納されている全てのパラメータ
	 */
	public function getParameters() : array;
}
