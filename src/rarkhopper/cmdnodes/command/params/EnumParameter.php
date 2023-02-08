<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function ucfirst;

abstract class EnumParameter extends CommandParameterBase{
	/**
	 * @return array<string>
	 */
	abstract public function getEnums() : array;

	private function getEnumObject() : CommandEnum{
		return new CommandEnum(
			ucfirst($this->getName()),
			$this->getEnums(),
		);
	}

	public function getNetworkTypeId() : int{
		return AvailableCommandsPacket::ARG_FLAG_ENUM;
	}

	public function asNetworkParameter() : NetworkParameter{
		$param = new NetworkParameter();
		$param->paramName = $this->getName();
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | $this->getNetworkTypeId();
		$param->enum = $this->getEnumObject();
		$param->flags = $this->getFlags();
		$param->isOptional = $this->isOptional();
		return $param;
	}
}
