<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
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
        $session = $this->request->getSession();
        if($session->check('error')){
            $session->delete('error');
        }
        $categories = $this->{'CRUD'}->getAllCategory();
        try{
            $this->set(compact('categories', $this->paginate($categories, ['limit'=> '3'])));
        } catch (NotFoundException $e) {
            $atribute = $this->request->getAttribute('paging');
            $requestedPage = $atribute['Categories']['requestedPage'];
            $pageCount = $atribute['Categories']['pageCount'];
            if($requestedPage > $pageCount) {
                return $this->redirect("https://test.com/admin/list-categories?page=".$pageCount."");
            }

        }

    }

    //Add Categories
    public function addCategory()
    {
        if ($this->request->is('post')) {
            $atribute = $this->request->getData();
            $dataCategory = $this->{'CRUD'}->addcategory($atribute);
            if($dataCategory['result'] == "invalid"){
                $error = $dataCategory['data'];
                $this->set('error', $error);
                $data = $atribute;
                $this->set('dataCategory', $data);
            }else{
                $this->Flash->success(__('Danh mục đã được thêm thành công.'));
                return $this->redirect(['action' => 'listCategories']);
            }
        }
    }

    //Edit Categories
    public function editCategory($id = null)
    {
        $checkCategoryID = $this->{'CRUD'}->checkIDCategory($id);
        if(count($checkCategoryID) < 1){
            $this->Flash->error(__('Danh mục không tồn tại.'));
                return $this->redirect(['action' => 'listCategories']);
        }
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $atribute = $this->request->getData();

            //Check thay đổi
            if(trim($atribute['category_name']) == trim($dataCategory[0]['category_name'])
             ){
                $this->Flash->error(__('Danh mục không có sự thay đổi.'));
                $data = $atribute;
            }else{
                $category = $this->Categories->patchEntity($dataCategory[0], h($atribute));
                if ($category->hasErrors()) {
                    $error = $category->getErrors();
                    $this->set('error', $error);
                    $data = $atribute;
                }else {
                    if ($this->Categories->save($category)) {
                        $this->Flash->success(__('Danh mục đã được cập nhật thành công.'));
                        return $this->redirect($atribute['referer']);
                    }else{
                    $this->Flash->error(__('Danh mục chưa được cập nhật. Vui lòng thử lại.'));
                    }
                }
            }
        }
        else{
            $data = $dataCategory[0];
            $data["referer"] = $this->referer();
        }
        $this->set('dataCategory', $data);
    }

    //Delete Soft Categories
    public function deleteCategory($id = null)
    {
        $urlPageList = $_SERVER['HTTP_REFERER'];
        $this->request->allowMethod(['post', 'delete']);
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $atribute = $this->request->getData();

        //Kiểm tra Danh mục còn sản phẩm không
        $checkProduct= $this->{'CRUD'}->checkProductByCategory($atribute);
        if(count($checkProduct) > 0){
            $this->Flash->error(__('Danh mục còn sản phẩm. Không thể xóa'));
            return $this->redirect(['action' => 'listCategories']);
        }
        $atribute['del_flag'] = 1;
        $category = $this->Categories->patchEntity($dataCategory[0], $atribute);

        if ($this->Categories->save($category)) {
            $this->Flash->success(__('Danh mục đã được xóa thành công.'));
            return $this->redirect("$urlPageList");
        }else{
            $this->Flash->error(__('Danh mục chưa được xóa. Vui lòng thử lại.'));
        }

    }

    //View Categories
    public function viewCategory($id = null)
    {
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $this->set(compact('dataCategory'));
    }
}
