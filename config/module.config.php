<?php
return array(
	'jQuery'  => array(
		'cdn' => array(
			'google' => array(
				'base_http' 	=> 'http://ajax.googleapis.com/ajax/libs',
				'base_ssl'  	=> 'https://ajax.googleapis.com/ajax/libs',
				'jquery_folder' => 'jquery',
				'jquery_file'   => 'jquery.min.js',	
				'ui_folder' 	=> 'jqueryui',
			),
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
