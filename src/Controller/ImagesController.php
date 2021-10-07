<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    //component
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Data');
        $this->loadComponent('CRUD');
        $this->loadModel("Images");
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

    //List Images
    public function listImages()
    {
        $images = $this->{'CRUD'}->getAllImage();
        //Search
        $key = $this->request->getQuery('key');
        if($key){
            $query = $this->{'CRUD'}->getSearch($key);
        }else{
            $query = $images;
        }
        $this->set(compact('query', $this->paginate($query, ['limit'=> '3'])));

    }

    //Add Product
    public function addImages()
    {
        $dataProduct =  $this->{'CRUD'}->getAllProduct();
        $product = $this->Images->newEmptyEntity();
        if ($this->request->is('post')) {
            $session = $this->request->getSession();
            $atribute = $this->request->getData();
            $dataImage = $this->{'CRUD'}->addimage($atribute);

            // $image = $this->request->getData('uploadfile');
            // $name = $image->getClientFilename();
            // $targetPath = WWW_ROOT.'img'.DS.$name;
            // $image->moveTo($targetPath);
            // $product->image = $name;

            if($dataImage['result'] == "invalid"){
                $error = $dataImage['data'];
                $session->write('error', $error);
                $this->Flash->error(__('Thêm Hình ảnh thất bại. Vui lòng thử lại.'));
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
                $this->Flash->success(__('Hình ảnh đã được thêm thành công.'));
                return $this->redirect(['action' => 'listImages']);
            }
        }

        $this->set(compact('product','dataProduct'));
    }

    //Edit Product
    public function editProduct($id = null)
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($dataProduct[0], $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('Sản phẩm đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'listProducts']);
            }
            $this->Flash->error(__('Sản phẩm chưa được cập nhật. Vui lòng thử lại.'));
        }
        // dd($dataProduct);
        $this->set(compact('dataProduct', 'dataCategory'));
    }

    //Delete soft Product

    public function deleteProduct($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 1;
        $product = $this->Products->patchEntity($dataProduct[0], $atribute);
        if ($this->Products->save($product)) {
            $this->Flash->success(__('Sản phẩm đã được xóa thành công.'));
            return $this->redirect(['action' => 'listProducts']);
        }else{
            $this->Flash->error(__('Sản phẩm chưa được xóa. Vui lòng thử lại.'));
            return $this->redirect(['action' => 'listProducts']);
        }
    }

    

    

    

    
}
