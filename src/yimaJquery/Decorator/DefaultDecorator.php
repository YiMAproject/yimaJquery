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
     * Render the placeholder as script codes
     *
     * @param null|int|string $indent
     * @return string
     */
    public function toString()
    {
        $items = $this->data;

        $return = '';
        foreach ($items as $item) {
            $return .= PHP_EOL.
                $this->startElement().$this->writeAttributes($item['attributes']).$this->endElement().
                (($item['mode'] == 'script') ? $item['content'] : '').
                $this->startElement(true).$this->endElement()
            ;
        }

        return $return;
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
     * Is the script provided valid?
     *
     * @param  mixed  $value  Is the given script valid?
     *
     * @return bool
     */
    public function isValid($value)
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
     * Start tag element
     *
     * @param bool $close Is close tag element?
     *
     * @return string
     */
    protected function startElement($close = false)
    {
        $return = '<'.(($close) ? '/' : '').'script';

        return $return;
    }

    /**
     * End tag element
     *
     * @return string
     */
    protected function endElement()
    {
        return '>';
    }

    /**
     * Write down attributes
     *
     * @param array $attributes Key Value pair attributes
     *
     * @return string
     */
    protected function writeAttributes(array $attributes)
    {
        $return = '';
        foreach ($attributes as $attr => $value)
        {
            $return .= " {$attr}=\"{$value}\"";
        }

        return $return;
    }
}