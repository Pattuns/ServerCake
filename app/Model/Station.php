<?php
App::uses('AppModel', 'Model');
/**
 * Station Model
 *
 * @property Place $Place
 */
class Station extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasMany = array(
		'Place' => array(
			'className' => 'Place',
			'foreignKey' => 'station_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
        'station_0' => array(
            'className' => 'Fare',
            'foreignKey' => 'station_0'
        ),
        'station_1' => array(
            'className' => 'Fare',
            'foreignKey' => 'station_1'
        )
	);
}
