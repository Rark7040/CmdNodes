<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\params;

abstract class CommandParameterBase implements ICommandParameter{
	/**
	 * @param bool $isOptional 入力が任意であるか否か
	 * @param int  $flags      {@see NetworkParameter::FLAG_FORCE_COLLAPSE_ENUM, NetworkParameter::FLAG_HAS_ENUM_CONSTRAINT}
	 */
	public function __construct(
		private bool $isOptional = false,
		private int $flags = 0,
	) {}

	public function isOptional() : bool {
		return $this->isOptional;
	}

	public function getFlags() : int{
		return $this->flags;
	}
}
