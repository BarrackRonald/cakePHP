<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * NormalUsers Controller
 *
 * @method \App\Model\Entity\NormalUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NormalUsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Data');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $dataProducts = $this->{'Data'}->getAllProducts();
        $dataSlideImages = $this->{'Data'}->getSlideImage();
        $dataNewsProducts = $this->{'Data'}->getNewsProduct();

        $this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts'));

    }

    public function search(){
        if($this->request->is('get')){
            $keyword = $this->request->getQueryParams();
            dd($keyword);
            $this->{'Data'}->getSearch($keyword);
            return $this->redirect(['action' => 'index']);
        }
    }

    public function contact(){

    }

    public function product(){

    }

    public function preview(){

    }

    public function about(){

    }

    /**
     * View method
     *
     * @param string|null $id Normal User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $normalUser = $this->NormalUsers->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('normalUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $normalUser = $this->NormalUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $normalUser = $this->NormalUsers->patchEntity($normalUser, $this->request->getData());
            if ($this->NormalUsers->save($normalUser)) {
                $this->Flash->success(__('The normal user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The normal user could not be saved. Please, try again.'));
        }
        $this->set(compact('normalUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Normal User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $normalUser = $this->NormalUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $normalUser = $this->NormalUsers->patchEntity($normalUser, $this->request->getData());
            if ($this->NormalUsers->save($normalUser)) {
                $this->Flash->success(__('The normal user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The normal user could not be saved. Please, try again.'));
        }
        $this->set(compact('normalUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Normal User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $normalUser = $this->NormalUsers->get($id);
        if ($this->NormalUsers->delete($normalUser)) {
            $this->Flash->success(__('The normal user has been deleted.'));
        } else {
            $this->Flash->error(__('The normal user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
