<?php

namespace Blanfordia\Visitors\Support\Exceptions;

use Exception;
use Blanfordia\Visitors\Visitors;

class Handler {

	private $visitors;

	private $illuminateHandler;

	private $originalExceptionHandler;

	private $originalErrorHandler;

	public function __construct(Visitors $visitors, $illuminateHandler = null)
	{
		$this->visitors = $visitors;

		$this->illuminateHandler = $illuminateHandler;

		$this->initializeHandlers();
	}

	private function initializeHandlers()
	{
		$this->originalExceptionHandler = set_exception_handler([$this, 'handleException']);

		$this->originalErrorHandler = set_error_handler([$this, 'handleError']);
	}

	public function handleException(Exception $exception)
	{
		try
		{
			$this->visitors->handleException($exception, $exception->getCode());
		}
		catch(\Exception $e)
		{
			// Ignore Visitors exceptions
		}

		// Call Laravel Exception Handler
		return call_user_func($this->originalExceptionHandler, $exception);
	}

	public function handleError($err_severity, $err_msg, $err_file, $err_line, array $err_context)
	{
		try
		{
			$error = ExceptionFactory::make($err_severity, $err_msg);

			$this->visitors->handleException($error, $error->getCode());
		}
		catch(\Exception $e)
		{
			// Ignore Visitors exceptions
		}

		// Call Laravel Exception Handler
		return call_user_func($this->originalErrorHandler, $err_severity, $err_msg, $err_file, $err_line);
	}

	public function report($e)
	{
		try
		{
			$this->visitors->handleException($e);
		}
		catch(Exception $exception)
		{
			// ignore
		}

		$this->illuminateHandler->report($e);
	}

	public function render($request, $e)
	{
		return $this->illuminateHandler->render($request, $e);
	}

	public function renderForConsole($output, Exception $e)
	{
		return $this->illuminateHandler->renderForConsole($output, $e);
	}

}
