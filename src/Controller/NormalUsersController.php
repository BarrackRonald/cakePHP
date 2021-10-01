<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Exception;

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
        $this->loadComponent('Mail');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //Viết ở beforeRender
    }

    public function billOrder(){
        if($this->request->is('post')){
            $session = $this->request->getSession();
            if(!$session->check('cartData')){
                $this->Flash->error(__('Giỏ hàng trống nên không thể đặt hàng'));
                return $this->redirect(['controller'=>'/', 'action' => 'index']);
            }else{
                $dataProds = $session->read('cartData');
            }

            //check user đã đăng nhập chưa
            if(!$session->check('idUser')){
                $dataProds['flag'] = 0;
                echo '<script> if (confirm("Bạn có muốn đăng nhập để đặt hàng không: ")) {
                    window.location.assign("/login");
                   } </script>';
            }else{
                $dataProds['flag'] = 1;
                $idUsers = $session->read('idUser');
                $dataUser = $this->{'Data'}->getInfoUser($idUsers);
                $this->set(compact('dataUser'));
            }

            $session->write('cartData', $dataProds);
            $this->set(compact('dataProds'));
        }
    }

    //Add User cho phần không login
    public function adduser(){
        if($this->request->is('post')){
            $atribute = $this->request->getData();
            $session = $this->request->getSession();
            $dataUser = $this->{'Data'}->adduser($atribute);

            if($dataUser['result'] == "invalid"){
                $error = $dataUser['data'];
                $this->set(compact('error'));
                $this->redirect(['action' => 'billOrder']);
            }

            // Checkmail trùng
            $checkmail = $this->{'Data'}->checkmail($atribute);
            if(count($checkmail)> 0){
                $text = 'Địa chỉ mail này đã tồn tại.';
                $this->set(compact('text'));
            }

            if($session->check('cartData')){
                $dataProds = $session->read('cartData');
                $dataProds['infoUser'] = $dataUser;
                $session->write('cartData', $dataProds);
                $this->set(compact('dataProds'));
            }

        }
    }

    //Add Order không login
    public function addordersnonelogin(){
        if($this->request->is('post')){
            $atribute = $this->request->getData();
            $session = $this->request->getSession();
            $product = null;

            if($session->check('cartData')){
                 $dataProds = $session->read('cartData');

                 //Point user trước khi mua
                 $pointBF = 0;
                 $pointAF = $pointBF + $dataProds['totalAllPoint'];


                // Inser User
                 $insertUser =  $this->{'Data'}->insertUsers($dataProds, $pointAF);

                // Checkmail trùng
                $checkmail = $this->{'Data'}->checkmail($atribute);
                if(count($checkmail)> 0){
                    $text = 'Địa chỉ mail này đã tồn tại.';
                    $this->set('text');
                }

                if($insertUser['result'] == "invalid"){
                    $error = $insertUser['data'];
                    $this->set(compact('error'));
                }

                //  Insert Order
                 $insertOrder = $this->{'Data'}->createOrders($atribute, $dataProds, $insertUser);
                 if(!$insertOrder['result'] == "invalid")
                 {
                     $to = $atribute['email'];
                     $subject = 'Mail Confirm Order';
                     $message = '
                         Thông tin đặt hàng gồm:
                             + Họ và tên khách hàng: '.$atribute['fullname'].'
                             + Địa chỉ: '.$atribute['address'].'
                             + Số điện thoại:'.$atribute['phonenumber'].'';

                     foreach($dataProds['cart'] as $value) {
                        $product .= ' * '.$value['name'].' × '.$value['quantity']." \r\n";
                     }
                     $message .= '
                             + Mặt hàng đã mua:
                                  '.$product.'
                             + Tổng số point nhận được:'.$atribute['totalAllPoint'].'
                             + Tổng số tiền cần thanh toán:'.$atribute['totalAllAmount'].'
                             ';
                     //xóa session
                     $session->delete('cartData');
                     $errSendMail = $this->{'Mail'}->send_mail($to, $subject, $message);
                     if($errSendMail == false){
                         $this->redirect(['action' => 'successOrder']);
                     }
                }
             }
         }
    }

    public function addorders(){
        
        if($this->request->is('post')){
           $atribute = $this->request->getData();
           $session = $this->request->getSession();
           $product = null;
           $insertUser = null;

           if($session->check('cartData')){
                $dataProds = $session->read('cartData');
                $idUsers = $session->read('idUser');
                $result = $this->{'Data'}->createOrders($atribute, $dataProds, $insertUser);
                $pointuser = $this->{'Data'}->getPointByUser($idUsers);

                //Point user trước khi mua
                $pointBF = $pointuser[0]['point_user'];
                $pointAF = $pointBF + $dataProds['totalAllPoint'];
                $this->{'Data'}->updatePoint($pointAF, $idUsers);

                if(!$result['result'] == "invalid")
                {
                    // dd($atribute['phonenumber']);
                    $to = $atribute['email'];
                    $subject = 'Mail Confirm Order';
                    $message = '
                        Thông tin đặt hàng gồm:
                            + Họ và tên khách hàng: '.$atribute['fullname'].'
                            + Địa chỉ: '.$atribute['address'].'
                            + Số điện thoại:'.$atribute['phonenumber'].'';

                    foreach($dataProds['cart'] as $key => $value) {
                       $product .= ' * '.$value['name'].' × '.$value['quantity']." \r\n";
                    }
                    $message .= '
                            + Mặt hàng đã mua:
                                 '.$product.'
                            + Tổng số point nhận được:'.$atribute['totalAllPoint'].'
                            + Tổng số tiền cần thanh toán:'.$atribute['totalAllAmount'].'
                            ';
                    //xóa session
                    $session->delete('cartData');
                    $errSendMail = $this->{'Mail'}->send_mail($to, $subject, $message);
                    if($errSendMail == false){
                        $this->redirect(['action' => 'successOrder']);
                    }
                }


            }
        }
    }
    public function successOrder(){

    }

    public function informationCart(){
        $session = $this->request->getSession();
			if($session->check('cartData')){
                $dataProds = $session->read('cartData');
                $this->set(compact('dataProds'));
            }

    }

    public function checkout(){

    }

    public function dellAllCart(){
        if($this->request->is('post')){
            $dataSession = [];
            $cartData = [];

            $product_id = $this->request->getData()['productId'];

            $data = $this->{'Data'}->getProductByID($product_id);

            $session = $this->request->getSession();

            if($session->check('cartData')){
                $dataSession = $session->read('cartData');
                $cartData = $dataSession['cart'];
            }

            //Tổng tất cả mặt hàng
            $totalAmounts = $cartData[$product_id]['totalAmount'];

            $totalAllAmount = isset($dataSession['totalAllAmount']) ? $dataSession['totalAllAmount']-$totalAmounts : $totalAmounts;

            $dataSession['totalAllAmount'] = $totalAllAmount;

            //End

            //Tổng tất cả point
            $totalPoint = $cartData[$product_id]['totalPoint'];

            $totalAllPoint = isset($dataSession['totalAllPoint']) ? $dataSession['totalAllPoint']-$totalPoint : $totalPoint;

            $dataSession['totalAllPoint'] = $totalAllPoint;

            //End

            $quantity = isset($cartData[$product_id]) ? $cartData[$product_id]['quantity'] : 0;

            $totalquantity = isset($dataSession['totalquantity']) ? $dataSession['totalquantity'] : 0;

            $dataSession['totalquantity'] = $totalquantity - $quantity;

            if(isset($cartData[$product_id])){
                unset($cartData[$product_id]);
            }

            $dataSession['cart'] = $cartData;

            $session->write('cartData', $dataSession);
           return  $this->response->withStringBody(json_encode($dataSession));
        }
    }

    public function dellCart(){
        if($this->request->is('post')){
            $dataSession = [];
            $cartData = [];

            $product_id = $this->request->getData()['productId'];

            $data = $this->{'Data'}->getProductByID($product_id);

            $session = $this->request->getSession();

            if($session->check('cartData')){
                $dataSession = $session->read('cartData');
                $cartData = $dataSession['cart'];
            }

            $quantity = (isset($cartData[$product_id]) ? $cartData[$product_id]['quantity'] : 0) -1;
            $amount = $data[0]['amount_product'];
            $point = $data[0]['point_product'];

            // Tính số lượng từng sản phẩm
            $totalAmount = $quantity * $amount;
            $totalPoint = $quantity * $point;

            

            $productArr = [
                $product_id => [
                  'name' => $data[0]['product_name'],
                  'image'=> $data[0]['Images']["file"],
                  'amount' => $data[0]['amount_product'],
                  'point' => $data[0]['point_product'],
                  'quantity'=> $quantity,
                  'totalAmount' =>  $totalAmount,
                  'totalPoint' => $totalPoint
                ],
            ];

            $cartData[$product_id] = $productArr[$product_id];

            //Tổng tất cả mặt hàng
            $totalAmounts = $cartData[$product_id]['amount'];

            $totalAllAmount = isset($dataSession['totalAllAmount']) ? $dataSession['totalAllAmount']-$totalAmounts : $totalAmounts;

            $dataSession['totalAllAmount'] = $totalAllAmount;

            //Tổng tất cả point
            $totalPoint = $cartData[$product_id]['point'];

            $totalAllPoint = isset($dataSession['totalAllPoint']) ? $dataSession['totalAllPoint']+$totalPoint : $totalPoint;

            $dataSession['totalAllPoint'] = $totalAllPoint;


            $totalquantity = isset($dataSession['totalquantity']) ? $dataSession['totalquantity'] : 0;

            $dataSession['totalquantity'] = $totalquantity - 1;

            if($quantity <= 0){
                unset($cartData[$product_id]);
            }

            $dataSession['cart'] = $cartData;

            $session->write('cartData', $dataSession);
           return  $this->response->withStringBody(json_encode($dataSession));
        }
    }

    public function addCart(){
        if($this->request->is('post')){
            $dataSession = [];
            $cartData = [];

            $product_id = $this->request->getData()['productId'];

            $data = $this->{'Data'}->getProductByID($product_id);

            $session = $this->request->getSession();

            if($session->check('cartData')){
                $dataSession = $session->read('cartData');
                $cartData = $dataSession['cart'];
            }

            //Số lượng = kiểm tra sản phẩm trong giỏ hàng có tồn tại ko, nếu tồn tại thì lấy theo số lượng trong giỏ hàng, nếu ko tồn tại thì =0
            $quantity = (isset($cartData[$product_id]) ? $cartData[$product_id]['quantity'] : 0) + 1;

            $amount = $data[0]['amount_product'];

            $point = $data[0]['point_product'];

            // Tính số lượng từng sản phẩm

            $totalAmount = $quantity * $amount;

            $totalPoint = $quantity * $point;

            //Tạo arr sản phẩm để lưu thông tin và số lượng.
            $productArr = [
                $product_id => [
                  'name' => $data[0]['product_name'],
                  'image'=> $data[0]['Images']["file"],
                  'amount' => $data[0]['amount_product'],
                  'point' => $data[0]['point_product'],
                  'quantity'=> $quantity,
                  'totalAmount' =>  $totalAmount,
                  'totalPoint' => $totalPoint
                ],
            ];


            //Lấy ID sản phẩm ở cartData = Mảng thông tin số lượng
            $cartData[$product_id] = $productArr[$product_id];
            //Tổng tất cả mặt hàng
            $totalAmounts = $cartData[$product_id]['amount'];

            $totalAllAmount = isset($dataSession['totalAllAmount']) ? $dataSession['totalAllAmount']+$totalAmounts : $totalAmounts;

            $dataSession['totalAllAmount'] = $totalAllAmount;

            //Tổng tất cả point
            $totalPoint = $cartData[$product_id]['point'];

            $totalAllPoint = isset($dataSession['totalAllPoint']) ? $dataSession['totalAllPoint']+$totalPoint : $totalPoint;

            $dataSession['totalAllPoint'] = $totalAllPoint;

            //Tổng số lượng mặt hàng
            $totalquantity = isset($dataSession['totalquantity']) ? $dataSession['totalquantity'] : 0;

            $dataSession['totalquantity'] = $totalquantity + 1;
            $dataSession['cart'] = $cartData;


            $session->write('cartData', $dataSession);

           return  $this->response->withStringBody(json_encode($dataSession));
        }
    }

    public function search(){
        if($this->request->is('get')){
            $keyword = $this->request->getQueryParams();
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
