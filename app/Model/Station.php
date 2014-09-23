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

    // 情報抽出用の関数
    function ExtractInfo($info, $keys){
        $out = array();
        foreach($info as $key => $categoryInfo){ 
            if(in_array($key, $keys)){

                $key_diff = array_filter($keys, function($value) use (&$key){
                    return $value != $key;
                });
                $index = array_shift($key_diff);

                foreach($categoryInfo as $fareInfo){
                    $out[] = array(
                      'station_purpose_id' => $fareInfo[$index],
                      'fare' => $fareInfo['fare'],
                      'child_fare' => $fareInfo['child_fare'],
                      'card_fare' => $fareInfo['card_fare'],
                      'child_card_fare' => $fareInfo['child_card_fare']);
                }
                return $out;
            }
        }
    }
}
