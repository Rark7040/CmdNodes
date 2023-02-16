<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\params\ICommandParameter;
use function explode;

abstract class SubCommandBase implements IExecutable{
	/** @var array<ICommandParameter> */
	private array $params = [];
	private ?string $permission = null;

	/**
	 * @param string        $label   このサブコマンドが{@see CommandBase}から見て1つ目のサブコマンドの場合、/foo barのbarの部分にあたる文字列
	 * @param array<string> $aliases 省略名を格納した配列
	 */
	public function __construct(
		private string $label,
		private array $aliases = []
	) {}

	/**
	 * コマンドが実行されたときに呼び出される関数
	 * @param CommandSender $sender コマンドを実行したプレイヤー
	 * @param array<string> $args   コマンドライン引数
	 * @throws CommandException 内部で不整合により処理を中断する場合にはこの例外を投げてください
	 */
	abstract public function onRun(CommandSender $sender, array $args) : void;

	public function getLabel() : string{
		return $this->label;
	}

	/**
	 * @return string[]
	 */
	public function getAliases() : array{
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

	/**
	 * 引数となる値を位置を指定して追加します
	 *
	 * @param ICommandParameter $param    引数となる値
	 * @param int               $position 引数の位置 barがこのサブコマンドのラベルで、0にbazを指定した場合/foo bar bazのようなシンタックスになる
	 * @return $this
	 */
	protected function setParameter(ICommandParameter $param, int $position) : SubCommandBase{
		$this->params[$position] = $param;
		return $this;
	}

	/**
	 * 引数となる値を末尾に追加します
	 *
	 * @param ICommandParameter $param 引数となる値
	 * @return $this
	 */
	protected function appendParameter(ICommandParameter $param) : SubCommandBase{
		$this->params[] = $param;
		return $this;
	}

	/**
	 * @return NetworkParameter このサブコマンドのラベル情報を{@see CommandParameter}として返します
	 */
	private function asNetworkParameter() : NetworkParameter{
		$label = $this->getLabel();
		$param = new NetworkParameter();
		$param->paramName = $label;
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
		$param->isOptional = false;
		$param->enum = new CommandEnum($label, [$label]);
		return $param;
	}

	/**
	 * @return array<NetworkParameter> このサブコマンド以降の値を{@see CommandParameter}として返します
	 */
	public function getParameters() : array{
		$params = [$this->asNetworkParameter()];

		foreach($this->params as $param){
			$params[] = $param->asNetworkParameter();
		}
		return $params;
	}
}
