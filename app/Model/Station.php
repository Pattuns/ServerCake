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

    public $findMethods = array('stationName' => true,
        'stationLocation' => true);

    protected function _findStationName($state, $query, $results = array()){
        if($state == 'before'){
            $query['recursive'] = -1;
            return $query;
        }
        else if($state == 'after'){
            $result = $results[0]['Station']['title'];
            return $result;
        }
    }

    protected function _findStationLocation($state, $query, $results = array()){
        if($state == 'before'){
            $query['recursive'] = 1;
            return $query;
        }
        else if($state == 'after'){
            $num = count($results[0]['Place']);
            $lonNum = $latNum = 0;
            foreach($results[0]['Place'] as $place){
                $lonNum += $place['lon'];
                $latNum += $place['lat'];
            }

            return array('lon' => $lonNum / $num, 'lat' => $latNum / $num);
        }
    }

    function getMiddlePoint($points){
        $num = count($points);
        $lonNum = $latNum = 0;

            foreach($points as $point){

                $lonNum += $point['lon'];
                $latNum += $point['lat'];

            }

            return array('lon' => $lonNum / $num, 'lat' => $latNum / $num);
    }

    // 料金情報挿入用関数
    function putFareInfo($fare){
        $array = array();

        list($array['fare'], $array['card_fare'],
            $array['child_fare'], $array['child_card_fare']) = $fare;
        return $array;
    }

    // 情報抽出用の関数
    function ExtractInfo($info, $keys){
        $out = array();
        $index = $info['Station']['id'];


        foreach($info as $key => $categoryInfo){ 

            if(in_array($key, $keys)){
                foreach($categoryInfo as $fareInfo){

                    $station_purpose_id = ($fareInfo['station_0'] == $index)?
                        $fareInfo['station_1']:$fareInfo['station_0'];

                    $out[] = array(
                      'station_purpose_id' => $station_purpose_id,
                      'fare' => $fareInfo['fare'],
                      'child_fare' => $fareInfo['child_fare'],
                      'card_fare' => $fareInfo['card_fare'],
                      'child_card_fare' => $fareInfo['child_card_fare']);
                }
            }
        }

        return $out;
    }
}
