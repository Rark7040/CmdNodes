<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\exception;

use pocketmine\command\CommandSender;
use Throwable;

class InvalidExecutorException extends CommandNodesException{
	public function __construct(
		private CommandSender $executor,
		private string $except,
		int $code = 0,
		?Throwable $previous = null
	){
		parent::__construct('invalid executor ' . $except . ', but executed by ' . $executor::class, $code, $previous);
	}

	public function getExecutor() : CommandSender{
		return $this->executor;
	}

	public function getExcept() : string{
		return $this->except;
	}
}
