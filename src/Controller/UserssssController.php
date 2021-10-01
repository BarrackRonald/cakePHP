<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Userssss Controller
 *
 * @method \App\Model\Entity\Users[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserssssController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Data');
       
    }

     public function index()
    {
        $results = $this->{'Data'}->getAllUser();
        $this->set(compact('results'));
    }

    /**
     * View method
     *
     * @param string|null $id Users id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $users = $this->Userssssss->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('users'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $users = $this->Userssss->newEmptyEntity();
        if ($this->request->is('post')) {
            $users = $this->Userssss->patchEntity($users, $this->request->getData());
            if ($this->Userssss->save($users)) {
                $this->Flash->success(__('The users has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users could not be saved. Please, try again.'));
        }
        $this->set(compact('users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Users id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $users = $this->Userssss->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $users = $this->Userssss->patchEntity($users, $this->request->getData());
            if ($this->Userssss->save($users)) {
                $this->Flash->success(__('The users has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users could not be saved. Please, try again.'));
        }
        $this->set(compact('users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Users id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $users = $this->Userssss->get($id);
        if ($this->Userssss->delete($users)) {
            $this->Flash->success(__('The users has been deleted.'));
        } else {
            $this->Flash->error(__('The users could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
