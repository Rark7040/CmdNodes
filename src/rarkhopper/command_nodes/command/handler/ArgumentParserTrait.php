<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\handler;

use rarkhopper\command_nodes\command\IExecutable;
use rarkhopper\command_nodes\command\parameter\ICommandParameter;
use rarkhopper\command_nodes\command\parameter\result\IParameterParseResult;
use rarkhopper\command_nodes\exception\ArgumentParseFailedException;

/**
 * @implements IExecutable<mixed>
 */
trait ArgumentParserTrait{

	/**
	 * @param array<int, string>            $rawArgs
	 * @param array<int, ICommandParameter> $params
	 * @return array<string, IParameterParseResult>
	 * @throws ArgumentParseFailedException
	 */
	private function parseArguments(array $rawArgs, array $params) : array{
		$results = [];

		for($offset = 0; isset($params[$offset]); ++$offset){
			$arg = $rawArgs[$offset] ?? null;
			$param = $params[$offset];

			if($arg === null){
				if($param->isOptional()) throw new ArgumentParseFailedException($param, null);
				return $results;
			}
			$results[$param->getLabel()] = $param->parseArgument($arg); //TODO: result
		}
		return $results;
	}
}
