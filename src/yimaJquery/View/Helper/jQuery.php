<?php
namespace yimaJquery\View\Helper;

use ArrayObject;
use yimaJquery\Decorator\DefaultDecorator;
use yimaJquery\Decorator\AbstractDecorator;
use yimaJquery\Deliveries\InterfaceDelivery;
use Zend\View\Helper\AbstractHelper;

/**
 * Class jQuery
 *
 * @method appendFile($pathToLocalFileOrHttp)
 * @method prependFile($pathToLocalFileOrHttp)
 * @method appendScript($script)
 * @method prependScript($script)
 *
 * @package yimaJquery\View\Helper
 */
class jQuery extends AbstractHelper
{
    /**
     * @var string Last enabled version or null on not enable
     */
    protected $enabled;

    protected $defVersion = '1.9.0';


    /**
     * @var boolean Is jQuery in noConflict mode
     */
    protected $isNoConflict = false;

    /**
     * @var string noConflict handler if not set used jQuery
     */
    protected $noConflictHandler;


    /**
     * @var ArrayObject
     */
    protected $container;

    /**
     * @var AbstractDecorator Decorator to render data container as script
     */
    protected $decorator;


    /**
     * @var Array[InterfaceDelivery]
     */
    protected $delivLibs;


    /**
     * Invoke helper
     *
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * Enable jQ by minimum version required
     *
     * @param null|string $ver minimum version of jQ required
     *
     * @return $this
     * @throws \Exception
     */
    public function enable($ver = null)
    {
        if ($ver == null) {
            $ver = ($this->getVersion())
                ?$this->getVersion()
                :$this->defVersion;
        }

        if ($this->isEnabled() && $this->getVersion() == $ver) {
            return $this;
        }

        $ver = (string) $ver;
        $this->enabled = $ver;

        return $this;
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
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (! preg_match('/^(?P<action>(ap|pre)pend)(?P<mode>File|Script)$/', $method, $matches)) {
            // we are not handle call request here
            return false;
        }

        if (count($args) === 0 ) {
            throw new \Exception(
                sprintf(
                    'Method "%s" requires at least one argument', $method
                )
            );
        }

        $attrs   = array();
        $action  = $matches['action']; 				// exp. append|prepend
        $mode    = strtolower($matches['mode']);	// exp. file|script
        $content = $args[0];

        switch ($mode) {
            case 'script':
                // appendScript($script)
                $item       = $this->createData($mode, $content);

                $this->$action($item);
                break;
            case 'file':
                // appendFile($pathToLocalFileOrHttp)
                $attrs['src'] = $content;
                $content      = null;
                $item         = $this->createData($mode, $content, $attrs);

                $this->$action($item);
                break;
        }

        return $this;
    }

    /**
     * Set jQuery to noConflict mode
     *
     * @param string $handler jQuery Handler
     *
     * @return $this
     */
    public function setNoConflict($bool = true)
    {
        $this->isNoConflict = $bool;

        return $this;
    }

    /**
     * Is jQuery in no conflict mode?
     *
     * @return bool
     */
    public function isNoConflict()
    {
        return $this->isNoConflict;
    }

    /**
     * Set noConflict Handler
     *
     * @param string $handler noConflict handler exp. $j
     *
     * @return $this
     */
    public function setNoConflictHandler($handler)
    {
        $this->noConflictHandler = (string) $handler;

        return $this;
    }

    /**
     * Get noConflict handler
     *
     * @return string
     */
    public function getNoConflictHandler()
    {
        return $this->noConflictHandler;
    }

    /**
     * Proxy to toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Render data set to string
     *
     * @return string
     */
    public function toString()
    {
        $decorator = $this->getDecorator();
        $decorator->setData((array) $this->getContainer());

        return (string) $decorator;
    }


    /**
     * Append data to container
     *
     * @param array $item Created Data
     */
    protected function append(array $item)
    {
        if (!$this->isEnabled()) {
            $this->enable();
        }

        $this->getContainer()->append($item);
    }

    /**
     * Prepend data to container
     *
     * @param array $item Created Data
     */
    protected function prepend(array $item)
    {
        if (! $this->isEnabled()) {
            $this->enable();
        }

        $container  = $this->getContainer();
        $currentArr = $container->getArrayCopy();

        array_unshift($currentArr, $item);
        $container->exchangeArray($currentArr);
    }

    /**
     * Set decorator renderer of container data
     *
     * @param AbstractDecorator $decorator Decorator instance
     *
     * @return $this
     */
    public function setDecorator(AbstractDecorator $decorator)
    {
        $decorator = clone $decorator;
        $this->decorator = $decorator;

        return $this;
    }

    /**
     * Get decorator for rendering data in container
     *
     * @return AbstractDecorator
     */
    public function getDecorator()
    {
        if (! $this->decorator) {
            $this->decorator = new DefaultDecorator();
        }

        // set options used to render scripts
        $this->decorator->no_conflict_mode    = $this->isNoConflict();
        $this->decorator->base_library        = $this->getLibSrc();
        $this->decorator->no_conflict_handler = $this->getNoConflictHandler();

        return $this->decorator;
    }

    /**
     * Set container for scripts
     *
     * @param ArrayObject $container
     */
    public function setContainer(ArrayObject $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container for scripts
     *
     * @return ArrayObject
     */
    public function getContainer()
    {
        if (! $this->container) {
            $this->container = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        }

        return $this->container;
    }

    /**
     * Is jQ enabled ?
     *
     * @return bool
     */
    public function isEnabled()
    {
        return ! ($this->enabled === null);
    }

    /**
     * Get current enabled version of jQ or false if not enabled
     *
     * @return bool|string
     */
    public function getVersion()
    {
        return ($this->enabled === null) ? false : $this->enabled;
    }

    /**
     * Get jquery lib src from deliveries
     *
     * @return string
     */
    public function getLibSrc($ver = null)
    {
        if ($ver == null) {
            $ver = $this->getVersion();
        }

        if (!$ver) {
            $ver = $this->defVersion;
        }

        $return = false;

        /** @var $service InterfaceDelivery */
        foreach ($this->getLibDelivers() as $name => $service) {
            $return = $service->getLibSrc($ver);
            if ($return) {
                break;
            }
        }

        if (!$return) {
            throw new \Exception('Can`t resolve to library.');
        }

        return $return;
    }

    /**
     * Add a deliverance of library
     *
     * @param InterfaceDelivery $deliverance
     */
    public function addLibDeliver(InterfaceDelivery $deliverance)
    {
        $name = $deliverance->getName();

        $this->delivLibs[$name] = $deliverance;

        return $this;
    }

    /**
     * Get lib Delivers
     *
     * @return array
     */
    public function getLibDelivers()
    {
        if (!$this->delivLibs) {
            $this->delivLibs = array();
        }

        return $this->delivLibs;
    }

    /**
     * Create data item containing all necessary components of script
     *
     * @param  string $mode       Type of data          script|file|link....
     * @param  array  $attributes Attributes of data
     * @param  string $content    Content of data
     *
     * @return Array
     */
    protected function createData($mode, $content = null, array $attributes = array())
    {
        $data               = array();

        $data['mode']       = $mode;
        $data['content']    = $content;
        $data['attributes'] = array_merge($attributes, array('type' => 'text/javascript'));

        return $data;
    }
}
