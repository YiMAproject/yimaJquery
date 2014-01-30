<?php
return array(
    'yima-jquery' => array(
        /**
         * We have a service in manager for each delivery
         *
         * foreach deliveries key we must have a service in serviceManager
         * with name 'YimaJquery\Deliveries\GoogleCdn' in example.
         *
         * exp. registered service as YimaJquery\Deliveries\SelfHosted
         *      all keys automatic converted to camelCase and get config
         *      from merged config to approach library address result.
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
    ),

	'controllers' => array(
		'invokables' => array(
			'yimaJquery/Controller/Attach' => 'yimaJquery\Controller\AttachController'
		),
	),
	'router' => array(
		'routes' => array(
			'yimaJquery' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/yimaJquery/js/[:overidding[:filepath]]',
					'constraints' => array(
						//'overidding' => 'override|regular',
						'filepath'   => '(/[\w-]+).*',
					),
					'defaults' => array(
						'controller' => 'yimaJquery/Controller/Attach',
						'action'     => 'script',
					),
				),
			),
		),
	),
);
