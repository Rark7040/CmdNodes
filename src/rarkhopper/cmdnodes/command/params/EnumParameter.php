<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function strtolower;
use function ucfirst;

abstract class EnumParameter extends CommandParameterBase{
	/** @var array<string> */
	private array $enums = [];

	/**
	 * @return array<string>
	 */
	public function getEnums() : array{
		return $this->enums;
	}

	public function appendEnum(string ...$enums) : void{
		foreach ($enums as $enum){
			$this->enums[] = strtolower($enum);
		}
	}

	private function getEnumObject() : CommandEnum{
		return new CommandEnum(
			ucfirst($this->getName()),
			$this->enums,
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
