<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\entity\Entity;

interface IValidator{
	public function getName() : string;
	public function validate(Entity $entity) : bool;
}
