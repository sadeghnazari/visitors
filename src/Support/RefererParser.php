<?php

namespace Blanfordia\Visitors\Support;

use Snowplow\RefererParser\Parser;

class RefererParser {

	/**
	 * Referer parser instance.
	 *
	 * @var Parser
	 */
	private $parser;

	/**
	 * Referer parser instance.
	 *
	 * @var \Snowplow\RefererParser\Referer
	 */
	private $referer;

	/**
	 * Create a referer parser instance.
	 *
	 * @return mixed
	 */
	public function __construct(Parser $parser)
	{
		$this->parser = $parser;
	}

	/**
	 * Parse a referer.
	 *
	 * @return mixed
	 */
	public function parse($refererUrl, $pageUrl)
	{
		$this->setReferer($this->parser->parse($refererUrl, $pageUrl));

		return $this;
	}

	/**
	 * Get the search medium.
	 *
	 * @return mixed
	 */
	public function getMedium()
	{
		if ($this->isKnown())
		{
			return $this->referer->getMedium();
		}

		return null;
	}

	/**
	 * Get the search source.
	 *
	 * @return mixed
	 */
	public function getSource()
	{
		if ($this->isKnown())
		{
			return $this->referer->getSource();
		}

		return null;
	}

	/**
	 * Get the search term.
	 *
	 * @return mixed
	 */
	public function getSearchTerm()
	{
		if ($this->isKnown())
		{
			return $this->referer->getSearchTerm();
		}

		return null;
	}

	/**
	 * Check if the referer is knwon.
	 *
	 * @return mixed
	 */
	public function isKnown()
	{
		return $this->referer->isKnown();
	}

	/**
	 * Set the referer.
	 *
	 * @return mixed
	 */
	public function setReferer($referer)
	{
		$this->referer = $referer;

		return $this;
	}

}
