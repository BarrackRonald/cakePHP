<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\View\Helper\FormHelper;
use Cake\Event\EventInterface;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
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
        $this->loadModel("Products");
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

    //List Products
    public function listProducts()
    {
        $products = $this->{'CRUD'}->getAllProduct();
        //Search
        $key = $this->request->getQuery('key');

        if($key){
            if($key == ''){
                $this->Flash->error(__('Không có dữ liệu Search!!!'));
    
            }else{
                $query = $this->{'CRUD'}->getSearch($key);
            }
            
        }else{
            $query = $products;
        }
        $this->set(compact('query', $this->paginate($query, ['limit'=> '3'])));

    }

    //Add Product
    public function addProduct()
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {
            $session = $this->request->getSession();
            $atribute = $this->request->getData();

            $dataProduct = $this->{'CRUD'}->addproduct($atribute);

            if($dataProduct['result'] == "invalid"){
                $error = $dataProduct['data'];
                $session->write('error', $error);
                $this->Flash->error(__('Thêm Sản phẩm thất bại. Vui lòng thử lại.'));
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }

                $this->Flash->success(__('Sản phẩm đã được thêm thành công.'));
                return $this->redirect(['action' => 'listProducts']);
            }
        }

        $this->set(compact('product','dataCategory'));
    }

    //Edit Product
    public function editProduct($id = null)
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            // dd($this->referer());
            $referer = $this->request->getData('referer');
            $product = $this->Products->patchEntity($dataProduct[0], $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('Sản phẩm đã được cập nhật thành công.'));
                return $this->redirect("$referer");
            }
            $this->Flash->error(__('Sản phẩm chưa được cập nhật. Vui lòng thử lại.'));
            return $this->redirect("$referer");
        }
        $this->set(compact('dataProduct', 'dataCategory'));
    }

    //Delete soft Product
    public function deleteProduct($id = null)
    {
        $urlPageList = $_SERVER['HTTP_REFERER'];
        $this->request->allowMethod(['post', 'delete']);
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        $atribute = $this->request->getData();
        $atribute['del_flag'] = 1;
        $product = $this->Products->patchEntity($dataProduct[0], $atribute);
        if ($this->Products->save($product)) {
            $this->Flash->success(__('Sản phẩm đã được xóa thành công.'));
            return $this->redirect("$urlPageList");
        }else{
            $this->Flash->error(__('Sản phẩm chưa được xóa. Vui lòng thử lại.'));
            return $this->redirect("$urlPageList");
        }
    }

    //View Product

    public function viewProduct($id = null)
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        $this->set(compact('dataProduct', 'dataCategory'));
    }
}
