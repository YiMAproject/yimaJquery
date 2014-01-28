<?php
namespace yimaJquery\Decorator;

/**
 * Interface InterfaceDecorator
 *
 * @package yimaJquery\Deliveries
 */
interface InterfaceDecorator
{
    /**
     * To String Object
     *
     * @return string
     */
    public function __toString();

    /**
     * Set data to decorate
     *
     * @param array $data
     *
     * @return mixed
     */
    public function setData(array $data);

    /**
     * Validate data value
     *
     * @param mixed $value is data script is valid?
     *
     * @return mixed
     */
    public function isValid($value);
}
