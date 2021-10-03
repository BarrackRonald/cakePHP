<?php
declare(strict_types=1);

namespace App\Controller;

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
            $atribute = $this->request->getData();
            $dataCategory = $this->{'CRUD'}->addcategory($atribute);
            if($dataCategory['result'] == "invalid"){
                $this->Flash->error(__('Thêm Danh mục thất bại. Vui lòng thử lại.'));
            }else{
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

    //Delete Categories

    public function deleteCategory($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);

        if ($this->Categories->delete($dataCategory[0])) {
            $this->Flash->success(__('Danh mục đã được xóa thành công.'));
        } else {
            $this->Flash->error(__('Danh mục chưa được xóa. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'listCategories']);
    }

    //View Categories

    public function viewCategory($id = null)
    {
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $this->set(compact('dataCategory'));
    }   
}
