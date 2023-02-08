<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes\params;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function strtolower;
use function ucfirst;

class EnumParameter implements ICommandParameter {
	/** @var array<string> */
	private array $enums = [];

	/**
	 * @param bool $isOptional 入力が任意であるか否か
	 * @param int  $flags      {@see NetworkParameter::FLAG_FORCE_COLLAPSE_ENUM, NetworkParameter::FLAG_HAS_ENUM_CONSTRAINT}
	 */
	public function __construct(
		private string $name,
		private bool $isOptional = false,
		private int $flags = 0,
	) {}

	public function getName() : string {
		return $this->name;
	}

	/**
	 * @return array<string>
	 */
	public function getEnums() : array {
		return $this->enums;
	}

	public function isOptional() : bool {
		return $this->isOptional;
	}

	public function getFlags() : int {
		return $this->flags;
	}

	public function appendEnum(string ...$enums) : void {
		foreach ($enums as $enum){
			$this->enums[] = strtolower($enum);
		}
	}

	private function getEnumObject() : CommandEnum{
		return new CommandEnum(
			ucfirst($this->name),
			$this->enums,
		);
	}

	public function getNetworkTypeId() : int {
		return AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID;
	}

	public function asNetworkParameter() : NetworkParameter {
		$param = new NetworkParameter();
		$param->paramName = $this->name;
		$param->paramType = $this->getNetworkTypeId();
		$param->enum = $this->getEnumObject();
		$param->flags = $this->flags;
		$param->isOptional = $this->isOptional;
		return $param;
	}
}
