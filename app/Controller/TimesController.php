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
	public $components = array('Paginator', 'Session');

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
}
