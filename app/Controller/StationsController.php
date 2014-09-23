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
    public $components = array('Paginator', 'Session');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Station->recursive = 0;
        // debug($this->Station->find('all'));
        $this->set('stations', $this->Paginator->paginate());
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

    public function compare(){

        $stationId_0 = $this->request->data('Station.station_0');
        $stationId_1 = $this->request->data('Station.station_1');

        $fareInfo = array();
        $pointInfo = array();

        $pointInfo[] = $this->Station->findById($stationId_0);
        $pointInfo[] = $this->Station->findById($stationId_1);

        $keys = array('station_0', 'station_1');

        $stationFare_0 = $this->Station->ExtractInfo($pointInfo[0], $keys);

        $stationFare_1 = $this->Station->ExtractInfo($pointInfo[1], $keys);

        foreach($stationFare_0 as $fareInfo_0){
            $stationPurposeId = $fareInfo_0['station_purpose_id'];
            $fare = $fareInfo_0['fare'];

            foreach($stationFare_1 as $fareInfo_1){
                if($stationPurposeId == $fareInfo_1['station_purpose_id'] &&
                    $fare == $fareInfo_1['fare']){
                    $fareNameInfo = $this->Station->findById($fareInfo_0['station_purpose_id']);
                    $fareInfo_0 += array('type' => 'midpoint', 'title' => $fareNameInfo['Station']['title']);
                    $fareInfo[] = $fareInfo_0;
                }
            }
        }

        // 最低額の取得
        $minFare = min(array_column($fareInfo, 'fare'));
        $fare = array_filter($fareInfo, function($fare) use (&$minFare){
            return $fare['fare'] == $minFare;
        });


        // debug($fareInfo);
        $this->set('pointInfo' , $pointInfo);

        $this->set('fareInfo' , $fare);


    }
}
