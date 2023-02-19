<?php

declare(strict_types = 1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use rarkhopper\command_nodes\command\params\ICommandParameter;
use RuntimeException;
use function array_shift;

abstract class CommandBase extends Command implements IExecutable{
	use ExecutableUtilsTrait;

	private ?ICommandArgumentsList $args = null;

	abstract public function onRun(CommandSender $sender, array $args) : void;

	public function setArguments(?ICommandArgumentsList $args) : void{ //HACK: いい書き方絶対他にある
		$this->args = $args;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) return;
		$firstArg = array_shift($args);

		if($firstArg === null || $this->args === null){
			$this->onRun($sender, $args);
			return;
		}
		$argInstances = $this->args->getArguments();
		$firstArgInstance = array_shift($argInstances);

		if($firstArgInstance instanceof IExecutable){
			$firstArgInstance->onRun($sender, $args);
			return;
		}

		if($firstArgInstance instanceof ICommandParameter){
			$params = [];

			foreach($argInstances as $param){
				if(!$param instanceof ICommandParameter) throw new RuntimeException(); //TODO: msg
				$params[] = $param;
			}
			$this->onRun($sender,  $this->parseParameters($args, $params));
			return;
		}
		throw new InvalidCommandSyntaxException();
	}

	/**
	 * @internal
	 * @return NetworkParameter[][]
	 */
	final public function getOverloads(CommandSender $receiver) : array{
		return $this->args === null? []: $this->args->asNetworkParameters($receiver);
	}
}
