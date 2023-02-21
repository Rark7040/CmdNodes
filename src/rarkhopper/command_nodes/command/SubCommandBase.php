<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function array_merge;
use function explode;

abstract class SubCommandBase implements IExecutable, ICommandArgument, ICommandArgumentList{//TODO: test permission
	private ?ICommandArgumentList $argList = null;
	private ?string $permission = null;

	/**
	 * @param string $label このサブコマンドが{@see CommandBase}から見て1つ目のサブコマンドの場合、/foo barのbarの部分にあたる文字列
	 */
	public function __construct(private string $label){}

	public function setArgumentList(?ICommandArgumentList $argList) : void{
		$this->argList = $argList;
	}

	public function getArgumentList() : ?ICommandArgumentList{
		return $this->argList;
	}

	public function getLabel() : string{
		return $this->label;
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

	public function asNetworkParameter() : NetworkParameter{
		$label = $this->getLabel();
		$param = new NetworkParameter();
		$param->paramName = $label;
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
		$param->isOptional = false;
		$param->enum = new CommandEnum($label, [$label]);
		return $param;
	}

	public function asNetworkParameters(CommandSender $receiver) : array{
		if(!$this->testPermission($receiver)) return [];
		$params = [$this->asNetworkParameter()];

		if($this->argList === null) return $params;
		return array_merge($params, $this->argList->asNetworkParameters($receiver));
	}
}
