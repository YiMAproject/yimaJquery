<?php
namespace yimaJquery\Decorator;

/**
 * Class DefaultDecorator
 *
 * @package yimaJquery\Decorator
 */
class DefaultDecorator extends AbstractDecorator
{
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

        // set options {
        if ($this->getNoConflictMode()) {
            // add script for noConflict mode
            $scriptItem = array(
                'mode'       => 'script',
                'content'    => ($this->getNoConflictHandler())
                        ? 'var '.$this->getNoConflictHandler().' = '. 'jQuery.noConflict();'
                        : 'jQuery.noConflict();',
                'attributes' => array(
                    'type' => 'text/javascript',
                ),
            );

            # add to items
            array_unshift($items, $scriptItem);
        }
        // .........
        $baseLibrary = $this->getBaseLibrary();
        if ($baseLibrary) {
            // add base script library
            $scriptItem = array(
                'mode'       => 'file',
                'content'    => null,
                'attributes' => array(
                    'src'  => $baseLibrary,
                    'type' => 'text/javascript',
                ),
            );

            # add to items
            array_unshift($items, $scriptItem);
        }
        // ... }

        $attachedBaseLib = false;
        $return = array('file' => '', 'script' => '');
        foreach ($items as $item) {
            if ($item['mode'] == 'file' && isset($item['attributes']['src'])) {
                // looking for duplicated base library
                if ($item['attributes']['src'] == $baseLibrary && !$attachedBaseLib) {
                    $attachedBaseLib = true;
                } else {
                    continue;
                }
            }

            $return[$item['mode']] .= PHP_EOL.
                $this->startElement().$this->writeAttributes($item['attributes']).$this->endElement().
                (($item['mode'] == 'script') ? $this->overrideScript($item['content']) : '').
                $this->startElement(true).$this->endElement()
            ;
        }

        $return = $return['file'].PHP_EOL.
            $return['script'].PHP_EOL;

        return $return;
    }

    /**
     * Correct scripts to noConflict mode
     *
     * @param string $script Script
     *
     * @return string
     */
    protected function overrideScript($script)
    {
        $noConflict = $this->getNoConflictMode();
        if (!$noConflict) {
            return $script;
        }

        $jQ = ($this->getNoConflictHandler())
            ? $this->getNoConflictHandler()
            : 'jQuery';

        $return = preg_replace_callback (
            '/\$[\.|\(](\"|\'|\w[\w\d]*)/',
            function ($matches) use ($jQ) {
                return str_replace('$', $jQ, $matches[0]);
            },
            $script
        );

        return $return;
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