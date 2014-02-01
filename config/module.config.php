<?php
return array(
    'yima-jquery' => array(
        /**
         * Each Delivery Resolve to a jQuery version library address
         *
         * foreach deliveries key we must have a service in serviceManager
         * with name 'YimaJquery\Deliveries\[ServiceName]' in example for key 'service-name'
         *
         */
        'deliveries'       => array(
            'cdn',
            // or with construct options
            /*
            'cdn' => array(
                'cdn-base'      => '//cdnjs.cloudflare.com/ajax/libs',
                'cdn-subfolder' => 'jquery/',
                'cdn-file-path' => '/jquery.min.js',
            ),
            */
        ),

        // decorator class or registered service
        // decorators get container of scripts and render as html or whatever else
        'decorator' => 'yimaJquery\Decorator\DefaultDecorator'
    ),
);
