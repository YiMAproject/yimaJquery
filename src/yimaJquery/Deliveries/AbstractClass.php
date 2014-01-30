<?php
namespace yimaJquery\Deliveries;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AbstractClass
 *
 * @package yimaJquery\Deliveries
 */
class AbstractClass implements InterfaceDelivery
{
    /**
     * @var string Name
     */
    protected $name;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * Get name of
     *
     * @return string
     */
    public function getName()
    {
        if (! $this->name) {
            $this->name = __CLASS__;
        }

        return $this->name;
    }

    /**
     * Get src to library for specific version
     *
     * @param string $ver Version of library
     */
    public function getLibSrc($ver)
    {
        if (! $this->isValidVersion($ver)) {
            throw new \Exception(
                sprintf(
                    'Invalid library version provided "%s"',
                    $ver
                )
            );
        }
    }

    /**
     * Is valid form version
     *
     * @param string $version exp. 1.10.2
     *
     * @return int
     */
    public function isValidVersion($version)
    {
        return preg_match('/^[1-9]\.[0-9](\.[0-9])?$/', $version);
    }

    /**
     * Set options
     *
     * @param array $options
     *
     * @return $this|mixed
     */
    public function setOptions(array $options)
    {
        if ($this->options && is_array($options)) {
            $options = ArrayUtils::merge($this->options, $options);
        }

        $this->options = $options;

        return $this;
    }
}
