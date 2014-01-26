<?php
namespace yimaJquery\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class jQuery
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

    protected $defLibraries = array(
        //'ver' => 'src',
    );

    /**
     * Invoke helper
     *
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    public function enable($ver = null)
    {
        if ($ver == null) {
            $ver = $this->defVersion;
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

    public function isEnabled()
    {
        return ! ($this->enabled === null);
    }

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
    public function getLibSrc()
    {
        // TODO: implement get library src
    }



    /**
     * Ghabl az inke headScript echo shavad baayad prepareScript ejraa shavad
     * ke libhaaye jQuery be headScript attach shavand
     *
     */
    public function prepareHeadScript()
    {
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
