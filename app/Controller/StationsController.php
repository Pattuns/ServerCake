<?php
App::uses('AppController', 'Controller');
/**
 * Stations Controller
 *
 * @property Station $Station
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class StationsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session','RequestHandler');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Station->recursive = 0;
        // debug($this->Station->find('all'));
        // $this->set('stations', $this->Paginator->paginate());
        $stations = $this->Station->find('all');
        $this->set(array(
            'stations' => $stations));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Station->exists($id)) {
            throw new NotFoundException(__('Invalid station'));
        }
        $options = array('conditions' => array('Station.' . $this->Station->primaryKey => $id));
        $this->set('station', $this->Station->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Station->create();
            if ($this->Station->save($this->request->data)) {
                $this->Session->setFlash(__('The station has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The station could not be saved. Please, try again.'));
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
        if (!$this->Station->exists($id)) {
            throw new NotFoundException(__('Invalid station'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Station->save($this->request->data)) {
                $this->Session->setFlash(__('The station has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The station could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Station.' . $this->Station->primaryKey => $id));
            $this->request->data = $this->Station->find('first', $options);
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
        $this->Station->id = $id;
        if (!$this->Station->exists()) {
            throw new NotFoundException(__('Invalid station'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Station->delete()) {
            $this->Session->setFlash(__('The station has been deleted.'));
        } else {
            $this->Session->setFlash(__('The station could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function select(){

        $this->set('select0', $this->Station->find('list', array(
            'fields' => array('id' , 'title')
        )));

        $this->set('select1', $this->Station->find('list', array(
            'fields' => array('id' , 'title')
        )));

    }

    public function show($station_0, $station_1){

        $this->Station->recursive =1;
        $temp = $this->Station->findById($station_0);
        debug($temp);

        $keys = array('station_0', 'station_1');
        $stationInfo_0 = $this->Station->ExtractInfo(
            $this->Station->findById($station_0),$keys);

        $stationInfo_array = array();
        foreach($stationInfo_0 as $info){
            $info['title'] = $this->Station->find('stationName',array(
                'conditions' => array('id' => $info['station_purpose_id'])));
            $stationInfo_array[] = $info;
        }

    }

    public function compareByDistance(){

        $stationIds = array();

        $stationIds[] = $this->request->query['station_0'];
        $stationIds[] = $this->request->query['station_1'];

        $pointInfo = array_map(function($Id){
            return $this->Station->findById($Id);
        }, $stationIds);

        foreach($pointInfo as $point){

            $coordinate = $this->Station->find('stationLocation',array(
                'conditions' => array('id' => $point['Station']['id'])));

            $points[] = array(
                'title' => $point['Station']['title'],
                'type' => 'point',
                'fare_midpoint_station_0' => 
                $this->Station->putFareInfo(array_pad($samp = array(), 4, 0)),
                    'fare_midpoint_station_1' =>
                    $this->Station->putFareInfo(array_pad($samp = array(), 4, 0)),
                    'fare_abs' => '0',
                    'id' => $point['Station']['id'],
                    'lon' => $coordinate['lon'],
                    'lat' => $coordinate['lat']);

        }

        $middleCoordinate = $this->Station->getMiddlePoint($points);

        $conskey = Configure::read("CONSKEY");

        $url = "https://api.tokyometroapp.jp/api/v2/places?rdf:type=odpt:Station&lon=" . 
            $middleCoordinate['lon'] . "&lat=" . $middleCoordinate['lat'] ."&radius=1000&acl:consumerKey="
            . $conskey;

        $obj = json_decode(file_get_contents($url));

        $pointNames = array();
        foreach($obj as $info){
            $pointInfo = get_object_vars($info);
            $pointName = $pointInfo['dc:title'];
            if(!in_array($pointName, $pointNames))
                $pointNames[] = $pointName;
        }

        // debug($pointNames);

        $pointInfo = array_map(function($pointName){
            return $this->Station->findByTitle(" " . $pointName . " ");
        }, $pointNames);

        // debug($pointInfo);
        //
        $array = array();
        $fareArray = array();
        $out = array();

        $outArray = array();

        foreach($pointInfo as $info){
            $name = $info['Station']['title'];
            $id = $info['Station']['id'];

            $out['id'] = $id;
            $out['title'] = $name;
            $out['type'] = 'midpoint';
            $coordinate = $this->Station->find('stationLocation',array(
                'conditions' => array('id' => $id)));

            $out['lon'] = $coordinate['lon'];
            $out['lat'] = $coordinate['lat'];

            $out['fare_abs'] = 0;
            $out['priority'] = true;

            foreach($points as $point){
                $stationId = $point['id'];

                foreach($info['station_0'] as $node){
                    if($node['station_1'] == $stationId &&
                        !in_array($node['station_0'],$array)){

                        $array[] = $node['station_0'];
                        $fareArray[] = array('fare' => $node['fare'],
                            'card_fare' => $node['card_fare'], 'child_fare' => $node['child_fare'],
                            'child_card_fare' => $node['child_card_fare']);

                    }

                }

                foreach($info['station_1'] as $node){
                    if($node['station_0'] == $stationId &&
                        !in_array($node['station_1'],$array)){

                        $array[] = $node['station_1'];
                        $fareArray[] = array('fare' => $node['fare'],
                            'card_fare' => $node['card_fare'], 'child_fare' => $node['child_fare'],
                            'child_card_fare' => $node['child_card_fare']);

                    }
                }

                $array = array();

            }
            $out['fare_midpoint_station_0'] = $fareArray[0];
            $out['fare_midpoint_station_1'] = $fareArray[1];

            $outArray[] = $out;
            $fareArray = array();
        }


        $this->set(array('compare' => $outArray));
    }

    public function compareByFare(){

        $stationIds = array();

        $stationIds[] = $this->request->query['station_0'];
        $stationIds[] = $this->request->query['station_1'];

        $fareInfo = array();

        $pointInfo = array_map(function($Id){
            return $this->Station->findById($Id);
        }, $stationIds);


        $keys = array('station_0', 'station_1');

        $stationInfos_0 = $this->Station->ExtractInfo($pointInfo[0], $keys);
        $stationInfos_1 = $this->Station->ExtractInfo($pointInfo[1], $keys);

        foreach($pointInfo as $point){

            $points[] = array(
                'title' => $point['Station']['title'],
                'type' => 'point',
                'fare_midpoint_station_0' => 
                $this->Station->putFareInfo(array_pad($samp = array(), 4, 0)),
                    'fare_midpoint_station_1' =>
                    $this->Station->putFareInfo(array_pad($samp = array(), 4, 0)),
                        'fare_abs' => '0',
                        'id' => $point['Station']['id'],
                        'lon' => $point['Place'][0]['lon'],
                        'lat' => $point['Place'][0]['lat']);

        }

        $middleCoordinate = $this->Station->getMiddlePoint($points);
        $tmp = array();

        foreach($stationInfos_0 as $stationInfo_0){
            $stationPurposeId = $stationInfo_0['station_purpose_id'];


            $stationFare_0 = array($stationInfo_0['fare'], $stationInfo_0['card_fare'],
                $stationInfo_0['child_fare'], $stationInfo_0['child_card_fare']);

            foreach($stationInfos_1 as $stationInfo_1){

                if($stationPurposeId == $stationInfo_1['station_purpose_id']
                    && !in_array($stationPurposeId, $tmp)){

                    $stationFare_1 = array($stationInfo_1['fare'], $stationInfo_1['card_fare'],
                        $stationInfo_1['child_fare'], $stationInfo_1['child_card_fare']);

                    $coordinate = $this->Station->find('stationLocation',array(
                        'conditions' => array('id' => $stationPurposeId)));

                    $fareInfo[] = array('type' => 'midpoint', 
                        'title' => $this->Station->find('stationName',array(
                            'conditions' => array('id' => $stationPurposeId))),

                        'fare_midpoint_station_0' => 
                        $this->Station->putFareInfo($stationFare_0),

                            'fare_midpoint_station_1' => 
                            $this->Station->putFareInfo($stationFare_1),

                                'fare_abs' => abs($stationFare_0[0] - $stationFare_1[0]),
                                'lon' => $coordinate['lon'], 
                                'lat' => $coordinate['lat'],
                                'dis' => sqrt(($coordinate['lon'] - $middleCoordinate['lon']) * ($coordinate['lon'] - $middleCoordinate['lon']) +
                                ($coordinate['lat'] - $middleCoordinate['lat']) * ($coordinate['lat'] - $middleCoordinate['lat'])),
                                'id' => $stationPurposeId);

                    $tmp[] = $stationPurposeId;
                }
            }
        }

        // 最低額の取得
        $minFare = min(array_column($fareInfo, 'fare_abs'));
        $fare = array_filter($fareInfo, function($fare) use (&$minFare){
            return $fare['fare_abs'] == $minFare;
        });

        uasort($fare,function($a, $b){
            $dis_1 = $a['dis'];
            $dis_2 = $b['dis'];
            if($dis_1 == $dis_2){
                return 0;
            }
            return ($dis_1 < $dis_2) ? -1 : 1;
        });

        foreach($points as $point)
            $fare[] = $point;

        $count = 0;
        foreach($fare as $info){

            $info['priority'] = ($count < 5 && $info['type'] == "midpoint")
                ? true : false;

            $out[] = $info; 
            $count++;
        }

        $this->set(array('compare' => $out));

    }
}
