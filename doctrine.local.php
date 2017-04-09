<?php

return array(
  'doctrine' => array(
	'configuration' => array(
		'orm_default' => array(
			'numeric_functions' => array(
				'Md5'  => 'Application\DoctrineFunctions\Md5',
			),
		)
	),
    'connection' => array(
      'orm_default' => array(
        'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
        'params' => array(
          'host'     => 'localhost',
          'port'     => '3306',
          'user'     => 'maksimjk_1',
          'password' => 'iNjTScoK',
          'dbname'   => 'maksimjk_1',
		)
	)

)));