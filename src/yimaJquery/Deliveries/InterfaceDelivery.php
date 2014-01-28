<?php
namespace yimaJquery\Deliveries;

/**
 * Interface InterfaceDelivery
 *
 * @package yimaJquery\Deliveries
 */
interface InterfaceDelivery
{
    public function getName();

    public function getLibrarySrc($ver);
}
