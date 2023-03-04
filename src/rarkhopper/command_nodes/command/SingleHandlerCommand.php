<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use rarkhopper\command_nodes\command\handler\ArgumentHoldableTrait;
use rarkhopper\command_nodes\exception\ArgumentParseFailedException;
use function array_values;

abstract class SingleHandlerCommand extends CommandBase{
	use ArgumentHoldableTrait;

	final public function prepareExec(CommandSender $sender, array $args) : void{
		if(!$this->testPermission($sender)) return;
		try{
			$this->exec($sender, $args, $this->parseParameters(array_values($args), $this->params));

		}catch(ArgumentParseFailedException $err){
			throw new InvalidCommandSyntaxException($err->getMessage());
		}
	}

	final public function getOverloads(CommandSender $receiver) : array{
		$overloads = [[]];

		foreach($this->params as $param){
			$overloads[][] = $param->asNetworkParameter();
		}
		return $overloads;
	}
}
