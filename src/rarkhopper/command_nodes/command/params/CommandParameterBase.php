<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;

/**
 * @internal
 */
abstract class CommandParameterBase implements ICommandParameter{
	/**
	 * @param bool $isOptional 入力が任意であるか否か
	 * @param int  $flags      {@see NetworkParameter::FLAG_FORCE_COLLAPSE_ENUM, NetworkParameter::FLAG_HAS_ENUM_CONSTRAINT}
	 */
	public function __construct(
		private string $name,
		private bool $isOptional = false,
		private int $flags = 0,
	){}

	/**
	 * @internal
	 */
	abstract protected function getNetworkType() : int;

	public function getName() : string{
		return $this->name;
	}

	public function isOptional() : bool{
		return $this->isOptional;
	}

	public function getFlags() : int{
		return $this->flags;
	}

	public function asNetworkParameter() : NetworkParameter{
		$param = new NetworkParameter();
		$param->paramName = $this->getName();
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkType();
		$param->flags = $this->getFlags();
		$param->isOptional = $this->isOptional();
		return $param;
	}
}
