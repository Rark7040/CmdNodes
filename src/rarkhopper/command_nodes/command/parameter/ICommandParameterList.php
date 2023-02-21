<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\parameter;

use rarkhopper\command_nodes\command\INetworkParameters;

interface ICommandParameterList extends INetworkParameters{
	/**
	 * @return array<ICommandParameter>
	 */
	public function getArguments() : array;
}
