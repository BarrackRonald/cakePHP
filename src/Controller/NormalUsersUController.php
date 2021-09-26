<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * NormalUsersU Controller
 *
 * @method \App\Model\Entity\NormalUsersU[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NormalUsersUController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        
    }

    /**
     * View method
     *
     * @param string|null $id Normal Users U id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $normalUsersU = $this->NormalUsersU->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('normalUsersU'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $normalUsersU = $this->NormalUsersU->newEmptyEntity();
        if ($this->request->is('post')) {
            $normalUsersU = $this->NormalUsersU->patchEntity($normalUsersU, $this->request->getData());
            if ($this->NormalUsersU->save($normalUsersU)) {
                $this->Flash->success(__('The normal users u has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The normal users u could not be saved. Please, try again.'));
        }
        $this->set(compact('normalUsersU'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Normal Users U id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $normalUsersU = $this->NormalUsersU->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $normalUsersU = $this->NormalUsersU->patchEntity($normalUsersU, $this->request->getData());
            if ($this->NormalUsersU->save($normalUsersU)) {
                $this->Flash->success(__('The normal users u has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The normal users u could not be saved. Please, try again.'));
        }
        $this->set(compact('normalUsersU'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Normal Users U id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $normalUsersU = $this->NormalUsersU->get($id);
        if ($this->NormalUsersU->delete($normalUsersU)) {
            $this->Flash->success(__('The normal users u has been deleted.'));
        } else {
            $this->Flash->error(__('The normal users u could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
