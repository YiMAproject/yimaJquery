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
}
