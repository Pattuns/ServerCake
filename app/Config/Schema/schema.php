<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $fares = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'length' => 21, 'key' => 'primary'),
		'station_0' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 21, 'key' => 'index'),
		'station_1' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 21, 'key' => 'index'),
		'fare' => array('type' => 'integer', 'null' => false, 'default' => null),
		'child_fare' => array('type' => 'integer', 'null' => false, 'default' => null),
		'card_fare' => array('type' => 'integer', 'null' => false, 'default' => null),
		'child_card_fare' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'station_0' => array('column' => 'station_0', 'unique' => 0),
			'station_1' => array('column' => 'station_1', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $places = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'length' => 21, 'key' => 'primary'),
		'urn_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'station_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 21, 'key' => 'index'),
		'sameAs' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'lon' => array('type' => 'float', 'null' => false, 'default' => null),
		'lat' => array('type' => 'float', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'station_id' => array('column' => 'station_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $stations = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'length' => 21, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

}
