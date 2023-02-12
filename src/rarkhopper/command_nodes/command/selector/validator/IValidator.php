<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\entity\Entity;

interface IValidator{

	/**
	 * @param scalar $arg
	 */
	public function __construct(mixed $arg);
	public function getType() : string;

	/**
	 * @return scalar
	 */
	public function getArguments() : mixed;
	public function validate(Entity $entity) : bool;
}
