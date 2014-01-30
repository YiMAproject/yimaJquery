<?php
namespace yimaJquery\Deliveries;

/**
 * Interface InterfaceDelivery
 *
 * @package yimaJquery\Deliveries
 */
interface InterfaceDelivery
{
    /**
     * Get name of
     *
     * @return string
     */
    public function getName();

    /**
     * Get src to library for specific version
     *
     * @param string $ver Version of library
     */
    public function getLibSrc($ver);

    /**
     * Set options
     *
     * @param array $options
     *
     * @return mixed
     */
    public function setOptions(array $options);
}
