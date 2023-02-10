<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\params;

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

	protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_FLAG_ENUM;
	}

	public function asNetworkParameter() : NetworkParameter{
		$param = parent::asNetworkParameter();
		$param->enum = $this->getEnumObject();
		return $param;
	}
}
