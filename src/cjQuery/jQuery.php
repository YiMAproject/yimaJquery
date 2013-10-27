<?php
namespace cjQuery;

class jQuery
{
	const MARKUP = '{{$}}';
	
	protected static $defaultNoconflictHandler;
	 
	protected static $parts = array(
		'jquery' => null,
		'ui'     => null,
		'mobile' => null,
	);
	
	/**
	 * jQuery no Conflict Mode
	 */
	protected static $noConflictMode = false;
	
	public static function enable($part = 'jquery')
	{
        $part = strtolower($part);
        if (! array_key_exists($part,self::$parts)) {
            throw new \Exception($part.' is not part of jQuery and can`t Enabled.');
        }

        if (! self::isEnabled($part)) {
            // load and enable jquery parts
            switch ($part) {
                case 'jquery':
                    $pClass = new Parts\jQuery(new static);
                    break;
            }

            self::$parts[$part] = $pClass;
        }

        return  self::$parts[$part];
	}
	
	public static function isEnabled($part = 'jquery')
	{
        $part = strtolower($part);

        if (! array_key_exists($part,self::$parts) ) {
            throw new \Exception($part.' is not defined as a part of jQuery.');
        }

        return ! empty(self::$parts[$part]);
	}

    /**
     * @param bool|string $bool SetDefaultNoconflict if string given and enable noConflict
     *
     * @return static
     */
    public static function setNoConflict($bool = true)
    {
        if (is_string($bool)) {
            self::setDefaultNoconflictHandler($bool);
            $bool = true;
        }

        self::$noConflictMode = (boolean) $bool;

        return new static;
    }

    public static function isNoConflict()
    {
        return self::$noConflictMode;
    }

    /**
     * Default NoConflict Handler
     *
     * @param $handler
     * @return static
     */
    public static function setDefaultNoconflictHandler($handler)
    {
        self::$defaultNoconflictHandler = $handler;

        return new static;
    }

    protected static function getDefaultNoconflictHandler()
    {
        if (! self::$defaultNoconflictHandler) {
            self::$defaultNoconflictHandler = '$j';
        }

        return self::$defaultNoconflictHandler;
    }

	public static function getHandler()
	{
		return ((self::isNoConflict()) ? self::getDefaultNoconflictHandler() : '$');
	}

    /**
     * @todo: make __call proxy
     *
     * get[Part]
     */
    public static function getJquery()
    {
        $pClass = false;

        if (self::isEnabled('jquery')) {
            $pClass = self::$parts['jquery'];
        }

        return $pClass;
    }
}