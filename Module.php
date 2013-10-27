<?php
namespace cjQuery;

use Zend\Mvc\MvcEvent;

class Module
{
	public function onBootstrap(MvcEvent $e)
	{
		$application  = $e->getApplication();
		$eventManager = $application->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'jQueryScripts'),1000);
	}

	public function jQueryScripts(MvcEvent $e)
	{
        // get scripts from jQuery container and move to headScript
        $jQuery = \cjQuery\jQuery::getJquery();
        if (!$jQuery) {
            return;
        }

        $sm = $e->getApplication()->getServiceManager();
        $view = $sm->get('ViewRenderer');
        $view->jQuery()->prepareHeadScript();
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'invokables' => array (
				'jQuery' => 'cjQuery\View\Helper\jQuery', 
			),
		);
	}
		
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}
	
	public static function getDir()
	{
		return __DIR__;
	}

}
