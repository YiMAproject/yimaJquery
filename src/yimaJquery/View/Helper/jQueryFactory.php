<?php
namespace yimaJquery\View\Helper;

use yimaJquery\Decorator\AbstractDecorator;
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
     * @var jQuery
     */
    protected $helper;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var array
     */
    protected $conf;

    public function __construct()
    {
        $this->helper = new jQuery();
    }
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $serviceLocator \Zend\View\HelperPluginManager */
        $this->serviceManager = $serviceLocator->getServiceLocator();

        $config = $this->serviceManager->get('Config');
        $config = (isset($config['yima-jquery']) && is_array($config['yima-jquery']))
            ? $config['yima-jquery']
            : array();

        // add deliver libraries
        $conf = (isset($config['deliveries'])) ? $config['deliveries'] : array();
        if (!empty($conf) && is_array($conf)) {
            $this->setDeliverLibs($conf);
        }

        // add decorator
        $conf = (isset($config['decorator'])) ? $config['decorator'] : '';
        if (!empty($conf)) {
            $this->setDecorator($conf);
        }

        return $this->helper;
    }

    /**
     * Set Deliverance library service
     *
     * @param array $conf Config
     */
    protected function setDeliverLibs($conf)
    {
        foreach ($conf as $name => $optName)
        {
            $options = $optName;
            $dl      = $name;
            if (! is_array($optName)) {
                // we have an deliverance service without options
                /* array (
                    'cdn',
                )*/
                $dl = $optName;
                $options = array();
            }

            $dl = str_replace(' ', '', ucwords(str_replace('-', ' ', $dl)));
            $service = 'YimaJquery\Deliveries\\'.$dl;
            if (! $this->serviceManager->has($service)) {
                trigger_error('Service '.$service.' not found.', E_USER_WARNING);

                continue;
            }

            $service = $this->serviceManager->get($service);
            $service->setOptions($options);

            $this->helper->addLibDeliver($service);
        }
    }

    /**
     * Set Decorator class
     *
     * @param array $conf Decorator class
     */
    protected function setDecorator($conf)
    {
        $decoratorClass = null;

        if (is_string($conf)) {
            if (class_exists($conf)) {
                $decoratorClass = new $conf();
            } elseif ($this->serviceManager->has($conf)) {
                $decoratorClass = $this->serviceManager->get($conf);
            }
        }

        if (!is_object($decoratorClass)) {
            throw new \Exception('Class or Service '.$conf. 'not found.');
        }

        if (!$decoratorClass instanceof AbstractDecorator) {
            throw new \Exception(
                sprintf(
                    'Decorator class must instance of AbstractDecorator, but %s given.'
                    ,get_class($decoratorClass)
                )
            );
        }

        $this->helper->setDecorator($decoratorClass);
    }
}
