<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

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
    public function listOrders()
    {
        $orders = $this->{'CRUD'}->getAllOrder();
        // dd($orders);
        //Search
        $key = $this->request->getQuery('key');
        if($key){
            $query1 = $this->{'CRUD'}->getSearchOrder($key);
        }else{
            $query1 = $orders;
        }
        // dd($query);
        $this->set(compact('query1', $this->paginate($query1, ['limit'=> '3'])));

    }

    //Chi tiết đơn hàng
    public function orderDetails($id = null)
    {
        $dataOrderDetails = $this->{'CRUD'}->getOrderDetailsByID($id);
        $this->set(compact('dataOrderDetails', $this->paginate($dataOrderDetails, ['limit'=> '3'])));
    }

    // Duyệt đơn hàng
    public function confirmOrder($id = null){
        $dataOrder = $this->{'CRUD'}->getOrderByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $confirm = $this->Orders->patchEntity($dataOrder[0], $this->request->getData());
            if ($this->Orders->save($confirm)) {
                $this->Flash->success(__('Đơn hàng đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'listOrders']);
            }
            $this->Flash->error(__('Đơn hàng chưa được cập nhật. Vui lòng thử lại.'));
        }
        // dd($dataOrder);
        $this->set(compact('dataOrder'));
    }
}
