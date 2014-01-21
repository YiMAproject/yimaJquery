<?php
namespace yimaJquery\View\Helper;

use yimaJquery\jQuery as jQueryController;

use Zend\View\Helper\AbstractHelper;

class jQuery extends AbstractHelper
{
	protected $jQuery;
	
	public function __construct()
	{
		if (! $this->jQuery) {
			$jQuery = new jQueryController();
		
			$this->jQuery = $jQuery;
		}	
	}
	
    public function __invoke($part = null)
    {
        return $this;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->jQuery,$method),$args);
    }

    /**
     * Ghabl az inke headScript echo shavad baayad prepareScript ejraa shavad
     * ke libhaaye jQuery be headScript attach shavand
     *
     */
    public function prepareHeadScript()
    {
        // get scripts from jQuery container and move to headScript
        $jQuery = \yimaJquery\jQuery::getJquery();
        if (! $jQuery) {
            return;
        }

        $view = $this->view;

        //prepend jQuery lib before other scripts
        $view->headScript()->prependFile($jQuery->getPathtoLibrary());

        foreach($jQuery->getContainer() as $item) {
            $prItem = $jQuery->getContainer()->getPreparedItem($item);

            if ($prItem['mode'] == 'file') {
                $view->headScript()->appendFile($prItem['content']);
            }

            if ($prItem['mode'] == 'script') {
                $view->headScript()->appendScript($prItem['content']);
            }
        }
    }

}
