<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AppController
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
        $this->loadModel("Orders");
    }

    //List Products
    public function listOrders()
    {
        $orders = $this->{'CRUD'}->getAllOrder();
        //Search
        $key = $this->request->getQuery('key');
        if($key){
            $query = $this->{'CRUD'}->getSearchOrder($key);
        }else{
            $query = $orders;
        }
        dd($query);
        $this->set(compact('query', $this->paginate($query, ['limit'=> '3'])));

    }

    //Add Product
    public function addProduct()
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {
            $atribute = $this->request->getData();
            $dataProduct = $this->{'CRUD'}->addproduct($atribute);

            // $image = $this->request->getData('uploadfile');
            // $name = $image->getClientFilename();
            // $targetPath = WWW_ROOT.'img'.DS.$name;
            // $image->moveTo($targetPath);
            // $product->image = $name;

            if($dataProduct['result'] == "invalid"){
                $this->Flash->error(__('Thêm Sản phẩm thất bại. Vui lòng thử lại.'));
            }else{
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

    //Delete Product

    public function deleteProduct($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dataProduct = $this->{'CRUD'}->getProductByID($id);

        if ($this->Products->delete($dataProduct[0])) {
            $this->Flash->success(__('Sản phẩm đã được xóa thành công.'));
        } else {
            $this->Flash->error(__('Sản phẩm chưa được xóa. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'listProducts']);
    }

    //View Product

    public function viewProduct($id = null)
    {
        $dataCategory =  $this->{'CRUD'}->getAllCategory();
        $dataProduct = $this->{'CRUD'}->getProductByID($id);
        $this->set(compact('dataProduct', 'dataCategory'));
    }   
}
