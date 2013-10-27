<?php
namespace cjQuery\Parts;

use cjQuery\jQuery as cjQuery;
use cjQuery\Exception;
use cjQuery\Parts\Container\DefaultContainer;

class AbstractPart 
{
    /**
     * @var \cjQuery\jQuery | null
     */
    protected $cjQuery = null;
	
	/**
	 * Path to library
	 *
	 * @var String
	 */
	protected $libraryPath = null;
	
	/**
	 * Library version
	 *
	 * @var String
	 */
	protected $version;

    /**
     * @var DefaultContainer
     */
    protected $container;
	

	public function __construct(cjQuery $cjQuery)
	{
		$this->cjQuery = $cjQuery;
	}
	
	/**
	 * this magic call allow:
	 * 
	 * File
	 * 	> appendFile($pathToLocalFileOrHttp, $overriding = false)
	 * 	> prependFile()
	 * 
	 * Script
	 * 	> appendScript($script, $overriding = false)
	 * 	> prependScript() 
	 * 
	 *  -------------------------------------------------------------------------------------------
	 *  $overriding | is a boolean value that wrap (function($) {  some code that uses $ })(jQuery) 
	 *  			  # jQuery is noConflict handler
	 *  
	 * @throws Exception\BadMethodCallException
	 */
	public function __call($method, $args)
	{
		if (! preg_match('/^(?P<action>(ap|pre)pend)(?P<mode>File|Script|Css|Stylesheet)$/', $method, $matches)) {
			throw new Exception\BadMethodCallException(sprintf(
				'Method "%s" not found.',$method
			));
		}
		
		if (count($args) === 0 ) {
			throw new Exception\BadMethodCallException(sprintf(
				'Method "%s" requires at least one argument', $method
			));
		}
				
		$attrs   = array();
		$action  = $matches['action']; 				// exp. append|prepend
		$mode    = strtolower($matches['mode']);	// exp. file|script|css-stylesheet 
		$content = $args[0];
		
		switch ($mode) {
			case 'script':
				// appendScript($script, $overriding = false)
				$overriding = (isset($args[1])) ? (boolean) $args[1] : false;
				$item = $this->getContainer()->createData($mode, $content, $overriding);
				$this->getContainer()->$action($item);
				break;
			case 'file':
				// appendFile($pathToLocalFileOrHttp, $overriding = false)
				if (!$this->getContainer()->isDuplicate($content)) {
					$attrs['src'] = $content;
					$overriding   = (isset($args[1])) ? (boolean) $args[1] : false;
					$item = $this->getContainer()->createData($mode, null /*content*/, $overriding, $attrs);
					$this->getContainer()->$action($item);
				}
				break;
			// TODO can attach other types
			case 'css':
			case 'stylesheet':
			case 'link':
				break;
		}
			
		return $this;
	}

    public function __toString()
    {
        return $this->getContainer()->toString();
    }

    public function setContainer(DefaultContainer $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        if (! $this->container) {
            $this->container = new DefaultContainer();
        }

        return $this->container;
    }

	/**
	 * Set path to Library
	 *
	 * @param  string $path
	 */
	public function setPathToLibrary($path, $version)
	{
		$this->setVersion($version);
		$this->libraryPath = (string) $path;
	
		return $this;
	}
	
	/**
	 * Get path to jQuery
	 *
	 * @return string
	 */
	public function getPathToLibrary()
	{
		return $this->libraryPath;
	}

	
	/**
	 * Set the version of the library used.
	 *
	 * @param string $version
	 */
	public function setVersion($version)
	{
		if (is_string($version) && preg_match('/^[1-9]\.[0-9](\.[0-9])?$/', $version)) {
			$this->version = $version;
		} else {
			throw new Exception\InvalidArgumentException(sprintf(
					'Invalid library version provided "%s"', $version
			));
		}
	
		return $this;
	}
	
	/**
	 * Get the version used with the library
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}
	
}