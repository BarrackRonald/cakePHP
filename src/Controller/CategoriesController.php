<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
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
        $this->loadComponent('CRUD');
        $this->loadModel("Categories");
    }

    public function beforeFilter(EventInterface $event)
    {
        $session = $this->request->getSession();
        $flag = $session->read('flag');
        if(!$session->check('flag') || $flag == 1){
            $this->Flash->error(__('Bạn không có quyền truy cập vào trang Admin.'));
            return $this->redirect(['controller'=>'NormalUsers', 'action' => 'index']);
        }
    }

    //List Categories
    public function listCategories()
    {
        $categories = $this->{'CRUD'}->getAllCategory();
        $this->set(compact('categories', $this->paginate($categories, ['limit'=> '3'])));

    }

    //Add Categories
    public function addCategory()
    {
        if ($this->request->is('post')) {
            $session = $this->request->getSession();
            $atribute = $this->request->getData();
            $dataCategory = $this->{'CRUD'}->addcategory($atribute);
            if($dataCategory['result'] == "invalid"){
                $error = $dataCategory['data'];
                $session->write('error', $error);
                $this->Flash->error(__('Thêm Danh mục thất bại. Vui lòng thử lại.'));
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
                $this->Flash->success(__('Danh mục đã được thêm thành công.'));
                return $this->redirect(['action' => 'listCategories']);
            }
        }
    }

    //Edit Categories
    public function editCategory($id = null)
    {

        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($dataCategory[0], $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('Danh mục đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'listCategories']);
            }
            $this->Flash->error(__('Danh mục chưa được cập nhật. Vui lòng thử lại.'));
        }
        
        $this->set(compact('dataCategory'));
    }

    //Delete Soft Categories

    public function deleteCategory($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataUser = $this->{'CRUD'}->getCategoryByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 1;
        $user = $this->Users->patchEntity($dataUser[0], $atribute);
        if ($this->Users->save($user)) {
            $this->Flash->success(__('Danh mục đã được xóa thành công.'));
            return $this->redirect(['action' => 'listCategories']);
        }
        $this->Flash->error(__('Danh mục chưa được xóa. Vui lòng thử lại.'));

    }

    //View Categories

    public function viewCategory($id = null)
    {
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $this->set(compact('dataCategory'));
    }   
}
