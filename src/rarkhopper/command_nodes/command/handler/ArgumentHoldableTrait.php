<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\handler;

use rarkhopper\command_nodes\command\IExecutable;
use rarkhopper\command_nodes\command\parameter\ICommandParameter;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;
use rarkhopper\command_nodes\exception\ArgumentParseFailedException;
use RuntimeException;

/**
 * @implements IExecutable<mixed>
 */
trait ArgumentHoldableTrait{
	/** @var array<int, ICommandParameter> */
	private array $params = [];

	public function registerParameter(ICommandParameter $param, int $offset, bool $force = false) : self{
		if(isset($this->params[$offset]) && !$force) throw new RuntimeException(); //TODO: msg
		$this->params[$offset] = $param;
		return $this;
	}

	/**
	 * @return array<int, ICommandParameter>
	 */
	public function getParameters() : array{
		return $this->params;
	}

	/**
	 * @param array<int, string>            $rawArgs
	 * @param array<int, ICommandParameter> $params
	 * @return array<string, IParameterParseResult>
	 * @throws ArgumentParseFailedException
	 */
	private function parseParameters(array $rawArgs, array $params) : array{
		$results = [];

		for($offset = 0; isset($params[$offset]); ++$offset){
			$arg = $rawArgs[$offset] ?? null;
			$param = $params[$offset];

			if($arg === null){
				if($param->isOptional()) throw new ArgumentParseFailedException($param, null);
				return $results;
			}
			$results[$param->getLabel()] = $param->parseArgument($arg);
		}
		return $results;
	}
}
