<?php
namespace yimaJquery\Deliveries;

/**
 * Class Cdn
 *
 * @package yimaJquery\Deliveries
 */
class Cdn extends AbstractClass
{
    /**
     * @var array default options
     */
    protected $options = array(
        'cdn-base'      => '//ajax.googleapis.com/ajax/libs/',
        'cdn-subfolder' => 'jquery/',
        'cdn-file-path' => '/jquery.min.js',
    );

    /**
     * Get src to library for specific version
     *
     * @param string $ver Version of library
     */
    public function getLibSrc($ver)
    {
        if (!$this->isValidVersion($ver)) {
            throw new \Exception(
                sprintf(
                    'Invalid library version provided "%s"',
                    $ver
                )
            );
        }

        $options = $this->options;
        $cdnUrl = rtrim($options['cdn-base'], '/').'/'.
            trim($options['cdn-subfolder'], '/').'/'.
            $ver.'/'.
            trim($options['cdn-file-path'], '/');

        return $cdnUrl;
    }
}
