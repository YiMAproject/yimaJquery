<?php
namespace yimaJquery\Decorator;

/**
 * Class DefaultDecorator
 *
 * @package yimaJquery\Decorator
 */
class DefaultDecorator implements InterfaceDecorator
{
    /**
     * @var array Data set
     */
    protected $data;

    /**
     * To String Object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Set data to decorate
     *
     * @param array $data
     *
     * @return mixed
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

	/**
	 * Render the placeholder as script codes
	 *
	 * @param null|int|string $indent
	 * @return string
	 */
	public function toString()
	{
	    // TODO: implement scripts output

        return 'jQuery scripts';
	}
}