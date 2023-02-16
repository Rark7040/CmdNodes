<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

interface ICompletionRegistry{
	public function register() : ICompletionRegistry; //TODO:
}
