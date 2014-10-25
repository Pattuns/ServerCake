<?php
App::uses('AppController', 'Controller');
/**
 * Times Controller
 *
 * @property Time $Time
 * @property PaginatorComponent $Paginator
 * @property nComponent $n
 * @property SessionComponent $Session
 */
class TimesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Time->recursive = 0;
		$this->set('times', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Time->exists($id)) {
			throw new NotFoundException(__('Invalid time'));
		}
		$options = array('conditions' => array('Time.' . $this->Time->primaryKey => $id));
		$this->set('time', $this->Time->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Time->create();
			if ($this->Time->save($this->request->data)) {
				$this->Session->setFlash(__('The time has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The time could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Time->exists($id)) {
			throw new NotFoundException(__('Invalid time'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Time->save($this->request->data)) {
				$this->Session->setFlash(__('The time has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The time could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Time.' . $this->Time->primaryKey => $id));
			$this->request->data = $this->Time->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Time->id = $id;
		if (!$this->Time->exists()) {
			throw new NotFoundException(__('Invalid time'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Time->delete()) {
			$this->Session->setFlash(__('The time has been deleted.'));
		} else {
			$this->Session->setFlash(__('The time could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

    public function show(){
        debug($this->Time->find('all', array(

        'conditions' => array('Time.id' => 190),
        'recursive' => 1)));
    }

    public function compareByTime(){

        $stationIds = array();
        $pointStationInfo = array();

        $stationIds[] = $this->request->query['station_0'];
        $stationIds[] = $this->request->query['station_1'];

        $now = $this->request->query['now'];

        $this->Time->Arrive->recursive = 0;
        $result = array_map(function($id){
            return $this->Time->Arrive->findById($id);
        }, $stationIds);

        $this->Time->Arrive->recursive = 0;
        $temp = $this->Time->Arrive->findById($stationIds[0]);
        $coordinate = $this->Time->Arrive->find('stationLocation',array(
            'conditions' => array('id' => $stationIds[0])));

        $pointStationInfo[] = array('id' => $temp['Arrive']['id'],
            'title' => $temp['Arrive']['title'],
            'coordinate' => $coordinate);

        $this->Time->Arrive->recursive = 0;
        $temp = $this->Time->Arrive->findById($stationIds[1]);
        $coordinate = $this->Time->Arrive->find('stationLocation',array(
            'conditions' => array('id' => $stationIds[1])));

        $pointStationInfo[] = array('id' => $temp['Arrive']['id'],
            'title' => $temp['Arrive']['title'],
            'coordinate' => $coordinate);

        $timeInfoStation0 = Hash::combine($this->Time->find('all', array(
            'conditions' => array('depart_station' => $stationIds[0],
                'now' => $now),
            'recursive' => -1)), '{n}.Time.id', '{n}.Time');

        $timeInfoStation1 = Hash::combine($this->Time->find('all', array(
            'conditions' => array('depart_station' => $stationIds[1],
                'now' => '05:00'),
            'recursive' => -1)), '{n}.Time.id', '{n}.Time');

        $infoArray = array();

        foreach($timeInfoStation0 as $station_0){

            foreach($timeInfoStation1 as $station_1){
                if($station_0['arrive_station'] == $station_1['arrive_station']){
                    $stationArriveTime_0 = strtotime($station_0['arrive']);
                    $stationArriveTime_1 = strtotime($station_1['arrive']);

                    $meetupTimeStamp = ($stationArriveTime_0 > $stationArriveTime_1)?
                        $stationArriveTime_0:$stationArriveTime_1;

                    $meetupTime = ($stationArriveTime_0 > $stationArriveTime_1)?
                        $station_0['arrive']:$station_1['arrive'];

                    $abs = abs($stationArriveTime_0 - $stationArriveTime_1);
                    $spendSum = $station_0['spend'] + $station_1['spend'];
                    $this->Time->Arrive->recursive = 0;
                    $stationName = $this->Time->Arrive->findById($station_0['arrive_station']);
                    $coordinate = $this->Time->Arrive->find('stationLocation',array(
                        'conditions' => array('id' => $station_0['arrive_station'])));

                    $infoArray[$station_0['arrive_station']]['meetupTime'] = $meetupTime;
                    $infoArray[$station_0['arrive_station']]['arriveTimeStation0'] = $station_0['arrive'];
                    $infoArray[$station_0['arrive_station']]['station0'] = $station_0;
                    $infoArray[$station_0['arrive_station']]['arriveTimeStation1'] = $station_1['arrive'];
                    $infoArray[$station_0['arrive_station']]['station1'] = $station_1;
                    $infoArray[$station_0['arrive_station']]['abs'] = $abs;
                    $infoArray[$station_0['arrive_station']]['spend'] = $spendSum;
                    $infoArray[$station_0['arrive_station']]['station'] = $stationName['Arrive']['title'];
                    $infoArray[$station_0['arrive_station']]['coordinate'] = $coordinate;
                }
            }
        }
        foreach($infoArray as $key => $row){
            $Abs[$key] = $row['abs'];
            $Spend[$key] = $row['spend'];
            $Meetup[$key] = $row['meetupTime'];
        }

        array_multisort($Meetup, SORT_ASC, $Spend, SORT_ASC, $Abs, SORT_ASC, $infoArray);

        $this->set('pointStationInfo', $pointStationInfo);
        $this->set('meetupTimes', $infoArray);

        $this->set(array('points' => $pointStationInfo));
        $this->set(array('compare' => $infoArray));

    }

    public function viewMeetup(){

        $stationTimes = array();
        $stationTimes[] = $this->request->query['stationTime_0'];
        $stationTimes[] = $this->request->query['stationTime_1'];

        $timesInfo_0 = $this->Time->findById($stationTimes[0]);
        $timesInfo_1 = $this->Time->findById($stationTimes[1]);

        if(count($timesInfo_0['Connect']) != 0){
            $array = array();
            foreach($timesInfo_0['Connect'] as $info){
                $this->Time->Arrive->recursive = -1;
                $stationName = $this->Time->Arrive->findById($info['station']);
                $info['station_name'] = $stationName['Arrive']['title'];
                $array[] = $info;
            }
            unset($timesInfo_0['Connect']);
            $timesInfo_0['Connect'] = $array;
        }

        if(count($timesInfo_1['Connect']) != 0){
            $array = array();
            foreach($timesInfo_1['Connect'] as $info){
                $this->Time->Arrive->recursive = -1;
                $stationName = $this->Time->Arrive->findById($info['station']);
                $info['station_name'] = $stationName['Arrive']['title'];
                $array[] = $info;
            }
            unset($timesInfo_1['Connect']);
            $timesInfo_1['Connect'] = $array;
        }
        // debug($timesInfo_0);
        // debug($timesInfo_1);

		// $this->set('timesInfo_0', $timesInfo_0);
		// $this->set('timesInfo_1', $timesInfo_1);
        $this->set(array('routeInfoStation0' => $timesInfo_0));
        $this->set(array('routeInfoStation1' => $timesInfo_1));

    }
}