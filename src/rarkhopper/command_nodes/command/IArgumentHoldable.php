<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

interface IArgumentHoldable{
	public function appendArgumentList(ICommandArgumentList $args) : void;

	/**
	 * @return array<ICommandArgumentList>
	 */
	public function getArgumentLists() : array;
}
