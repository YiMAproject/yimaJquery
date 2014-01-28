<?php
namespace yimaJquery\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class jQueryFactory
 *
 * @package yimaJquery\View\Helper
 */
class jQueryFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        /** @var $serviceManager ServiceManager */
        $serviceManager = $serviceLocator->getServiceLocator();

        $conf = $serviceManager->get('Config');
        $conf = ( isset($conf['yima-jquery']) && is_array($conf['yima-jquery']) )
            ?((isset($conf['yima-jquery']['deliveries'])) ?$conf['yima-jquery']['deliveries'] : array())
            :array();

        if (!is_array($conf)) {
            throw new \Exception('yima-jquery config deliveries must be an array.');
        }

        $jQhelper = new jQuery();

        foreach ($conf as $dl => $options)
        {
            $dl = str_replace(' ', '', ucwords(str_replace('-', ' ', $dl)));
            $service = 'YimaJquery\Deliveries\\'.$dl;

            if ($serviceManager->has($service)) {
                $jQhelper->addLibDeliver($serviceManager->get($service));
            }
        }

        return $jQhelper;
    }
}
