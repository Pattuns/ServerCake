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

        debug($pointInfo);

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

        $samp = $this->Station->putFareInfo(array_pad($samp = array(), 4, 0));


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

        debug($points);

         $middleCoordinate = array(
             'lon' => ($points[0]['lon'] + $points[1]['lon']) / 2,
             'lat' => ($points[0]['lat'] + $points[1]['lat']) / 2);

        $tmp = array();

        foreach($stationInfos_0 as $stationInfo_0){
            $stationPurposeId = $stationInfo_0['station_purpose_id'];

            $stationFare_0 = $stationInfo_0['fare'];
            $stationCardFare_0 = $stationInfo_0['card_fare'];
            $stationChildFare_0 = $stationInfo_0['child_fare'];
            $stationChildCardFare_0 = $stationInfo_0['child_card_fare'];

            foreach($stationInfos_1 as $stationInfo_1){

                if($stationPurposeId == $stationInfo_1['station_purpose_id']
                    && !in_array($stationPurposeId, $tmp)){

                    $stationFare_1 = $stationInfo_1['fare'];
                    $stationCardFare_1 = $stationInfo_1['card_fare'];
                    $stationChildFare_1 = $stationInfo_1['child_fare'];
                    $stationChildCardFare_1 = $stationInfo_1['child_card_fare'];

                    $coordinate = $this->Station->find('stationLocation',array(
                            'conditions' => array('id' => $stationPurposeId)));

                    $fareInfo[] = array('type' => 'midpoint', 
                        'title' => $this->Station->find('stationName',array(
                            'conditions' => array('id' => $stationPurposeId))),
                        'fare_midpoint_station_0' => array(
                            'fare' => $stationFare_0,
                            'card_fare' => $stationCardFare_0,
                            'child_fare' => $stationChildFare_0,
                            'child_card_fare' => $stationChildCardFare_0),
                            
                        'fare_midpoint_station_1' => array(
                            'fare' => $stationFare_1,
                            'card_fare' => $stationCardFare_1,
                            'child_fare' => $stationChildFare_1,
                            'child_card_fare' => $stationChildCardFare_1),

                        'fare_abs' => abs($stationFare_0 - $stationFare_1),
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
             if($count < 5 && $info['type'] == "midpoint"){
                $info['priority'] = true;
                $out[] = $info; 
             }
             else{
                 $info['priority'] = false;
                 $out[] = $info;
             }
             $count++;
         }



        // foreach($fare as $info){
        //     ksort($info);
        //     $out[] = $info;
        // }

        $this->set(array('compare' => $out));

    }
}
