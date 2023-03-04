<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command\argument;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\handler\ArgumentHoldableTrait;
use rarkhopper\command_nodes\command\IExecutable;
use rarkhopper\command_nodes\command\INetworkParameters;
use rarkhopper\command_nodes\exception\ArgumentParseFailedException;
use function array_merge;
use function array_values;
use function explode;

abstract class SubCommandBase implements IExecutable, ICommandArgument, INetworkParameters{
	use ArgumentHoldableTrait;

	private ?string $permission = null;

	/**
	 * @param string $label このサブコマンドが{@see CommandBase}から見て1つ目のサブコマンドの場合、/foo barのbarの部分にあたる文字列
	 */
	public function __construct(private string $label){}

	public function getLabel() : string{
		return $this->label;
	}

	final public function prepareExec(CommandSender $sender, array $args) : void{
		if(!$this->testPermission($sender)) return;
		try{
			$this->exec($sender, $args, $this->parseParameters(array_values($args), $this->params));

		}catch(ArgumentParseFailedException $err){
			throw new InvalidCommandSyntaxException($err->getMessage());
		}
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
		$params = [];

		foreach(array_merge([$this], $this->params) as $param){
			$params[] = $param->asNetworkParameter();
		}
		return $params;
	}
}
