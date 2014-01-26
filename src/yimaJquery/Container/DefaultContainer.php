<?php
namespace yimaJquery\Container;

use stdClass;
use yimaJquery\Exception;
use Zend\View\Helper\Placeholder\Container;

class DefaultContainer extends Container
{
	/**
	 * yimaJquery\jQuery
	 */
	protected $jQuery;
	
	/**
	 * Render the placeholder as script codes
	 *
	 * @param null|int|string $indent
	 * @return string
	 */
	public function toString($indent = null)
	{
		$items     = $this->getArrayCopy();
		
		$inlineScripts = array();
		$fileScripts   = array();
		
		foreach ($items as $item) {
            $prItem = $this->getPreparedItem($item);
            if ($prItem['mode'] == 'file') {
                $fileScripts[]   = $prItem['content'];
            } elseif ($prItem['mode'] == 'script') {
                $inlineScripts[] = $prItem['content'];
            }
		} // endof foreach
		
		$output = $this->getScriptTagOpen().implode($inlineScripts,PHP_EOL).$this->getScriptTagClose();
		
		$staticServer = '';
		$staticServer = rtrim($staticServer,'/');
		foreach ($fileScripts as $src) {
			$script = '<script type="text/javascript" src="%s"></script>';
			$output .= ( PHP_EOL.sprintf($script,$staticServer.$src) );
		}
		
		return $output;
	}

    /**
     *
     * @param $item | item is a data that containing all necessary components of script
     */
    public function getPreparedItem($item)
    {
        $content = array('mode' => null, 'content' => null);

        if ($item['mode'] == 'file' || $item['mode'] == 'script') {

            // this is file {
            if (isset($item['attributes']['src'])) {
                $filepath = $item['attributes']['src'];

                if (! file_exists($filepath) ) {
                    // this is a uri exp. [http://]/path/to/file
                    // this structure used by yimaJquery\Controller\Script to printout codes
                    $content = '/yimaJquery/js/'.(
                    ($item['overriding'])
                        ? \yimaJquery\jQuery::getHandler()
                        : \yimaJquery\jQuery::getHandler().'markup'.\yimaJquery\jQuery::MARKUP
                    ).$filepath;
                }
                else {
                    $content = file_get_contents($filepath);
                }

            } // ... }
            else {
                $content = $item['content'];
                if ($item['overriding']) {
                    // (function($) { /* some code that uses $ */ })(jQuery) # jQuery is noConflict handler
                    if ('$' != \yimaJquery\jQuery::getHandler()) {
                        $markup = \yimaJquery\jQuery::MARKUP;
                        $content = '(function($) {'.$content."})({$markup});";
                    }
                }

                $content = str_replace(\yimaJquery\jQuery::MARKUP,\yimaJquery\jQuery::getHandler(),$content);
            }

        }

        return array('mode' => $item['mode'], 'content' => $content);
    }
	
	public function getScriptTagOpen()
	{
		return PHP_EOL.'<script type="text/javascript">'.PHP_EOL;
	}
	
	public function getScriptTagClose()
	{
		return PHP_EOL.'</script>'.PHP_EOL;
	}
	
	/**
	 * Is the script provided valid?
	 *
	 * @param  mixed  $value  Is the given script valid?
	 * @return bool
	 */
	protected function isValid($value)
	{
		if (! is_array($value) 
			|| !isset($value['mode'])
			|| !isset($value['overriding'])
			|| (!isset($value['content']) && !isset($value['attributes']))
		){
			return false;
		}
	
		return true;
	}
	
	/**
	 * Is the file specified a duplicate?
	 *
	 * @param  string $file Name of file to check
	 * @return bool
	 */
	public function isDuplicate($file)
	{
		foreach ($this as $item) {
			if (($item['content'] === null)
				&& array_key_exists('src', $item['attributes'])
				&& ($file == $item['attributes']['src']))
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function append($value)
	{
		if (!$this->isValid($value)) {
			throw new Exception\InvalidArgumentException(
				'Invalid argument passed to append(); please use one of the helper methods, appendScript() or appendFile()'
			);
		}
	
		return parent::append($value);
	}
	
	public function prepend($value)
	{
		if (!$this->isValid($value)) {
			throw new Exception\InvalidArgumentException(
				'Invalid argument passed to prepend(); please use one of the helper methods, prependScript() or prependFile()'
			);
		}
	
		return parent::prepend($value);
	}

	public function set($value)
	{
		if (!$this->isValid($value)) {
			throw new Exception\InvalidArgumentException(
				'Invalid argument passed to set(); please use one of the helper methods, setScript() or setFile()'
			);
		}
	
		return parent::set($value);
	}
	
	/**
	 * Create data item containing all necessary components of script
	 *
	 * @param  string $mode       Type of data          script|file|link....
	 * @param  array  $attributes Attributes of data
	 * @param  string $content    Content of data
	 * @return stdClass
	 */
	public function createData($mode, $content = null, $overriding = false, array $attributes = array())
	{
		$data               = array();
		$data['mode']       = $mode;
		$data['attributes'] = $attributes;
		$data['content']    = $content;
		$data['overriding'] = $overriding;
		
		return $data;
	}

}