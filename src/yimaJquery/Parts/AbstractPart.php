<?php
namespace yimaJquery\Parts;

use yimaJquery\Container\DefaultContainer;

class AbstractPart 
{
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
	 * @throws \Exception
	 */
	public function __call($method, $args)
	{
		if (! preg_match('/^(?P<action>(ap|pre)pend)(?P<mode>File|Script|Css|Stylesheet)$/', $method, $matches)) {
			throw new \Exception(sprintf(
				'Method "%s" not found.',$method
			));
		}
		
		if (count($args) === 0 ) {
			throw new \Exception(sprintf(
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
}