<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter as NetworkParameter;
use function strtolower;

final class SubCommandsList implements IOverloadsList{
	/** @var array<string, SubCommandBase> */
	private array $subCmds = [];

	/**
	 * @param SubCommandBase $subCmd このコマンドの1つ目の引数となる文字列を持つサブコマンド
	 * @return $this
	 */
	public function registerSubCommand(SubCommandBase $subCmd) : SubCommandsList{
		$this->subCmds[strtolower($subCmd->getLabel())] = $subCmd;
		return $this;
	}

	/**
	 * @internal
	 * @return NetworkParameter[][]
	 */
	public function asNetworkParameters(CommandSender $receiver) : array{
		$overloads = [];
		foreach($this->subCmds as $subCmd){
			if(!$subCmd->testPermission($receiver)) continue;
			$overloads[] = $subCmd->getNetworkParameters();
		}
		return $overloads;
	}
}
