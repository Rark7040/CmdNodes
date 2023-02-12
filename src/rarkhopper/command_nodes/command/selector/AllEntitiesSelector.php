<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use function array_merge;

final class AllEntitiesSelector extends BaseSelector{
	public static function getIdentifier() : string{
		return 'e';
	}

	public function select(?CommandSender $executor) : array{
		$entities = [];

		foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world){
			$entities = array_merge($entities, $world->getEntities());
		}
		return $entities;
	}
}
