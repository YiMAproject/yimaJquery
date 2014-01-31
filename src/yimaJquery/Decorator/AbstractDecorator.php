<?php
namespace yimaJquery\Decorator;
use Zend\Stdlib\AbstractOptions;

/**
 * Class AbstractDecorator
 *
 * @package yimaJquery\Decorator
 */
abstract class AbstractDecorator extends AbstractOptions
    implements InterfaceDecorator
{
    /**
     * @var array Data set
     */
    protected $data;

    /**
     * @var bool (Option) noConflict mode
     */
    protected $noConflictMode = false;

    /**
     * @var string (Option) base library src
     */
    protected $baseLib;

    /**
     * To String Object
     *
     * @return string
     */
    abstract public function __toString();

    /**
     * Is the script provided valid?
     *
     * @param  mixed  $value  Is the given script valid?
     *
     * @return bool
     */
    abstract public function isValid($value);

    /**
     * Set data to decorate
     *
     * @param array $data
     *
     * @return mixed
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    // getter and setter options ------------------------------------------------------------------

    /**
     * Set base library (jquery.js) src
     *
     * @param string $libSrc Base library src
     *
     * @return $this
     */
    public function setBaseLibrary($libSrc)
    {
        $this->baseLib = $libSrc;

        return $this;
    }

    /**
     * Get base library src
     *
     * @return mixed
     */
    public function getBaseLibrary()
    {
        return $this->baseLib;
    }

    /**
     * Set noConflict mode
     *
     * @param boolean $bool Boolean value
     *
     * @return $this
     */
    public function setNoConflictMode($bool)
    {
        $this->noConflictMode = (boolean) $bool;

        return $this;
    }

    /**
     * Get noConflict mode
     *
     * @return bool
     */
    public function getNoConflictMode()
    {
        return $this->noConflictMode;
    }
}