<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function in_array;
use function ucfirst;

class EnumParameter extends CommandParameterBase{
	/** @var array<string> */
	public array $enums = [];

	final protected function getNetworkType() : int{
		return AvailableCommandsPacket::ARG_FLAG_ENUM;
	}

	public function asNetworkParameter() : NetworkParameter{
		$param = parent::asNetworkParameter();
		$param->enum = $this->getEnumObject();
		return $param;
	}

	public function validate(string $rawArg) : bool{
		return in_array($rawArg, $this->enums, true);
	}

	private function getEnumObject() : CommandEnum{
		return new CommandEnum(
			ucfirst($this->getLabel()),
			$this->enums,
		);
	}
}
