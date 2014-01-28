<?php
namespace yimaJquery\View\Helper;

use ArrayObject;
use yimaJquery\Decorator\DefaultDecorator;
use yimaJquery\Decorator\InterfaceDecorator;
use yimaJquery\Deliveries\InterfaceDelivery;
use Zend\View\Helper\AbstractHelper;

/**
 * Class jQuery
 *
 * @method appendFile($pathToLocalFileOrHttp, $overriding = false)
 * @method prependFile($pathToLocalFileOrHttp, $overriding = false)
 * @method appendScript($script, $overriding = false)
 * @method prependScript($script, $overriding = false)
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
     * @var ArrayObject
     */
    protected $container;

    /**
     * @var
     */
    protected $decorator;

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
        if (! preg_match('/^[1-9]\.[0-9](\.[0-9])?$/', $ver)) {
            throw new \Exception(
                sprintf(
                    'Invalid library version provided "%s"',
                    $ver
                )
            );
        }

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
                // appendScript($script, $overriding = false)
                $overriding = (isset($args[1])) ? (boolean) $args[1] : false;
                $item       = $this->createData($mode, $content, $overriding);

                $this->$action($item);
                break;
            case 'file':
                // appendFile($pathToLocalFileOrHttp, $overriding = false)
                $overriding   = (isset($args[1])) ? (boolean) $args[1] : false;
                $attrs['src'] = $content;
                $content      = null;
                $item         = $this->createData($mode, $content, $overriding, $attrs);

                $this->$action($item);
                break;
        }

        return $this;
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
        if (!$this->isEnabled()) {
            $this->enable();
        }

        $container  = $this->getContainer();
        $currentArr = $container->getArrayCopy();

        array_unshift($currentArr, $item);
        $container->exchangeArray($currentArr);
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
     * Set decorator renderer of container data
     *
     * @param InterfaceDecorator $decorator Decorator instance
     *
     * @return $this
     */
    public function setDecorator(InterfaceDecorator $decorator)
    {
        $decorator = clone $decorator;
        $this->decorator = $decorator;

        return $this;
    }

    /**
     * Get decorator for rendering data in container
     *
     * @return InterfaceDecorator
     */
    public function getDecorator()
    {
        if (! $this->decorator) {
            $this->decorator = new DefaultDecorator();
        }

        return $this->decorator;
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
     * Get jquery lib src from deliveries and at last
     * from default defined in class
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

        return '//cdn.raya-media.com/js/jquery-1.10.2.min.js'.'?ver='.$ver;
    }

    /**
     * Add a deliverance of library
     *
     * @param InterfaceDelivery $deliverance
     */
    public function addLibDeliver(InterfaceDelivery $deliverance)
    {
        // TODO: implement add library deliverance
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
     * Create data item containing all necessary components of script
     *
     * @param  string $mode       Type of data          script|file|link....
     * @param  array  $attributes Attributes of data
     * @param  string $content    Content of data
     *
     * @return Array
     */
    protected function createData($mode, $content = null, $overriding = false, array $attributes = array())
    {
        $data               = array();

        $data['mode']       = $mode;
        $data['content']    = $content;
        $data['attributes'] = array_merge($attributes, array('type' => 'text/javascript'));
        $data['overriding'] = $overriding;

        return $data;
    }
}
