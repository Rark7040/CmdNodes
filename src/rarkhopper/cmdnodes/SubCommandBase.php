<?php

declare(strict_types = 1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\cmdnodes\params\ICommandParameter;
use function explode;

abstract class SubCommandBase implements IPermissionTestable {
	/** @var array<ICommandParameter> */
	private array $params = [];
	private ?string $permission = null;

	/**
	 * @param array<string> $aliases
	 */
	public function __construct(
		private string $label,
		private array $aliases = []
	) {}

	public function getLabel() : string {
		return $this->label;
	}

	/**
	 * @return string[]
	 */
	public function getAliases() : array {
		return $this->aliases;
	}

	public function setPermission(?string $permission) : void{
		$this->permission = $permission;
	}

	public function testPermission(CommandSender $target, ?string $permission = null) : bool{
		$permission ??= $this->permission;

		if($permission === null || $permission === "") return true;
		foreach(explode(";", $permission) as $perm){
			if($target->hasPermission($perm)){
				return true;
			}
		}
		return false;
	}

	protected function appendParameter(ICommandParameter $param, ?int $position = null) : SubCommandBase{
		if($position === null){
			$this->params[] = $param;

		}else{
			$this->params[$position] = $param;
		}
		return $this;
	}

	/**
	 * @param array<string> $args
	 */
	abstract protected function onRun(CommandSender $sender, string $usedAlias, array $args) : void;

	public function asParameter() : NetworkParameter{
		$label = $this->getLabel();
		$param = new NetworkParameter();
		$param->paramName = $label;
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
		$param->isOptional = false;
		$param->enum = new CommandEnum($label, [$label]);
		return $param;
	}

	/**
	 * @return array<NetworkParameter>
	 */
	public function getParameters() : array{
		$params = [];

		foreach($this->params as $param){
			$params[] = $param->asNetworkParameter();
		}
		return $params;
	}
}
