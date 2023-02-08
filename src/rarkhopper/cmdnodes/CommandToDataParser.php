<?php

declare(strict_types=1);
namespace rarkhopper\cmdnodes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\command\CommandBase;
use function array_values;
use function count;
use function in_array;
use function strtolower;
use function ucfirst;

final class CommandToDataParser{
	use SingletonTrait;

	public function parse(Command $cmd, CommandSender $receiver) : CommandData{
		return $this->putOverLoad($this->parseNoOverload($cmd), $cmd, $receiver);
	}

	public function putOverLoad(CommandData $data, Command $cmd, CommandSender $receiver) : CommandData{
		$data->overloads = $this->getOverloads($cmd, $receiver);
		return $data;
	}

	public function parseNoOverload(Command $cmd) : CommandData{
		return new CommandData(
			strtolower($cmd->getLabel()),
			$this->getStringDescription($cmd),
			0,
			0,
			$this->getEnum($cmd),
			[]
		);
	}

	private function getEnum(Command $cmd) : ?CommandEnum{
		$aliases = $cmd->getAliases();

		if(count($aliases) < 1) return null;
		$label = strtolower($cmd->getLabel());

		if(!in_array($label, $aliases, true)){
			$aliases[] = $label;
		}
		return new CommandEnum(
			ucfirst($label) . 'Aliases',
			array_values($aliases)
		);
	}

	private function getStringDescription(Command $cmd) : string{
		$description = $cmd->getDescription();
		$lang = Server::getInstance()->getLanguage();
		return $description instanceof Translatable?
			$lang->translate($description):
			$lang->translateString($description);
	}

	/**
	 * @return CommandParameter[][]
	 */
	private function getOverloads(Command $cmd, CommandSender $receiver) : array{
		if(!$cmd instanceof CommandBase) return [];
		return $cmd->getOverloads($receiver);
	}
}
