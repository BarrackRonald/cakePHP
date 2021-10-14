<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Exception;
use Cake\Event\EventInterface;
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
        $this->loadComponent('CRUD');
        $this->loadModel("Users");
    }

    public function beforeRender(EventInterface $event)
    {
        $dataCategories = $this->{'Data'}->getCategory();
        $dataProducts = $this->{'Data'}->getAllProducts();
        $dataSlideImages = $this->{'Data'}->getSlideImage();
        $dataNewsProducts = $this->{'Data'}->getNewsProduct();

        $this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts', 'dataCategories'));
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
            if($session->check('hasBack')){
                $session->delete('hasBack');
            }
            if(!$session->check('cartData')){
                $this->Flash->error(__('Giỏ hàng trống nên không thể đặt hàng'));
                return $this->redirect(['action' => 'informationCart']);
            }else{
                $dataProds = $session->read('cartData');
                if($dataProds['totalAllAmount'] == 0){
                    $this->Flash->error(__('Giỏ hàng trống nên không thể đặt hàng'));
                    return $this->redirect(['action' => 'informationCart']);
                }
            }

            //check user đã đăng nhập chưa
            if(!$session->check('idUser')){
                $dataProds['flag'] = 0;
                echo "";
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
        $session = $this->request->getSession();
        $hasBack = 1;
        $session->write('hasBack', $hasBack);

        if($this->request->is('post')){
            $atribute = $this->request->getData();


            //checkmail tồn tại
            $checkmail = $this->{'Data'}->checkmail($atribute);
            if(count($checkmail)> 0){
                $error['email'] = ['Địa chỉ mail này đã tồn tại.'];
                $session->write('error', $error);
                $this->redirect(['action' => 'billOrder']);
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
            }

            //check Back
            if($session->check('cartData')){
                $dataProds = $session->read('cartData');
                if(isset($dataProds['infoUser'])){
                    if($dataProds['infoUser']['password'] == $atribute['password']){
                        $dataUser = $this->{'Data'}->adduserNoHash($atribute);
                    }else{
                        $dataUser = $this->{'Data'}->adduser($atribute);
                    }
                } else {
                    $dataUser = $this->{'Data'}->adduser($atribute);
                }
            } else{
                $dataUser = $this->{'Data'}->adduser($atribute);
            }

            if($dataUser['result'] == "invalid"){
                $error = $dataUser['data'];
                $session->write('error', $error);
                $this->redirect(['action' => 'billOrder']);
            }else{
                if($session->check('error')){
                    $session->delete('error');
                }
                // Checkmail trùng
                $checkmail = $this->{'Data'}->checkmail($atribute);

                if(count($checkmail)> 0){
                    $error['email'] = ['This email address already exists.'];
                    $session->write('error', $error);
                    $this->redirect(['action' => 'billOrder']);
                }else{
                    if($session->check('error')){
                        $session->delete('error');
                    }
                }

                if($session->check('cartData')){

                    $dataProds = $session->read('cartData');
                    $dataProds['infoUser'] = $dataUser['data'];
                    $session->write('cartData', $dataProds);
                    $this->set(compact('dataProds'));

                }

            }
        }
        if($session->check('cartData')){
            $dataProds = $session->read('cartData');
            $this->set(compact('dataProds'));
        }
    }

    //Add Order không login
    public function addordersnonelogin(){
        if($this->request->is('post')){
            $session = $this->request->getSession();
            $product = null;

            //Check Dữ liệu bắt buộc không đổi
            if($session->check('cartData')){
               $cartData = $session->read('cartData');
               $infoUser = $cartData['infoUser'];

            }
            if($session->check('cartData')){
                 $dataProds = $session->read('cartData');


                 //Point user trước khi mua
                 $pointBF = 0;
                 $pointAF = $pointBF + $dataProds['totalAllPoint'];


                // Inser User
                 $insertUser =  $this->{'Data'}->insertUsers($dataProds, $pointAF);
                if($insertUser['result'] == "invalid"){
                    $error = $insertUser['data'];
                    $this->set(compact('error'));
                }

                //  Insert Order
                 $insertOrder = $this->{'Data'}->createOrdersNone($infoUser, $dataProds, $insertUser);
                 if(!$insertOrder['result'] == "invalid")
                 {
                     $to = $infoUser['email'];
                     $toAdmin = 'tienphamvan2005@gmail.com';
                     $subject = 'Mail Confirm Order';
                     $message = '
                         Thông tin đặt hàng gồm:
                             + Họ và tên khách hàng: '.$infoUser['username'].'
                             + Địa chỉ: '.$infoUser['address'].'
                             + Số điện thoại:'.$infoUser['phonenumber'].'';

                     foreach($dataProds['cart'] as $value) {
                        $product .= ' * '.$value['name'].' × '.$value['quantity']." \r\n";
                     }
                     $message .= '
                             + Mặt hàng đã mua:
                                  '.$product.'
                             + Tổng số point nhận được:'.$dataProds['totalAllPoint'].'
                             + Tổng số tiền cần thanh toán:'.$dataProds['totalAllAmount'].'
                             ';
                     //xóa session
                     $session->delete('cartData');
                     $errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
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
                $dataUser = $this->{'Data'}->getInfoUser($idUsers);
                $result = $this->{'Data'}->createOrders($dataProds, $dataUser);
                $pointuser = $this->{'Data'}->getPointByUser($idUsers);

                //Point user trước khi mua
                $pointBF = $pointuser[0]['point_user'];
                $pointAF = $pointBF + $dataProds['totalAllPoint'];
                $this->{'Data'}->updatePoint($pointAF, $idUsers);

                if(!$result['result'] == "invalid")
                {
                    $to = $dataUser[0]['email'];
                    $toAdmin = 'tienphamvan2005@gmail.com';
                    $subject = 'Mail Confirm Order';
                    $message = '
                        Thông tin đặt hàng gồm:
                            + Họ và tên khách hàng: '.$dataUser[0]['username'].'
                            + Địa chỉ: '.$dataUser[0]['address'].'
                            + Số điện thoại:'.$dataUser[0]['phonenumber'].'';

                    foreach($dataProds['cart'] as $key => $value) {
                       $product .= ' * '.$value['name'].' × '.$value['quantity']." \r\n";
                    }
                    $message .= '
                            + Mặt hàng đã mua:
                                 '.$product.'
                            + Tổng số point nhận được:'.$dataUser[0]['totalAllPoint'].'
                            + Tổng số tiền cần thanh toán:'.$dataUser[0]['totalAllAmount'].'
                            ';
                    //xóa session
                    $session->delete('cartData');
                    $errSendMail = $this->{'Mail'}->send_mail($to, $toAdmin, $subject, $message);
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
                  'image'=> $data[0]['Images']["image"],
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
                  'image'=> $data[0]['images'][0]["image"],
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

    //Show thông tin cá nhân ở trang người dùng và chỉnh sửa

    public function myaccount(){
        $session = $this->request->getSession();
        if($session->check('idUser')){
            $idUsers = $session->read('idUser');
            $dataUser = $this->{'Data'}->getInfoUser($idUsers);
            $this->set(compact('dataUser'));
        }
    }

    public function editAccount($id = null){
        $dataUser = $this->{'CRUD'}->getUserByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($dataUser[0], h($this->request->getData()));
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Tài khoản đã được cập nhật thành công.'));
                return $this->redirect(['action' => 'myaccount']);
            }
            $this->Flash->error(__('Tài khoản chưa được cập nhật. Vui lòng thử lại.'));
        }

        $this->set(compact('dataUser'));
    }

    //End

    public function contact(){

    }

    public function product(){

    }

    public function preview(){

    }

    public function about(){

    }

    //View product by Category
    public function viewProductByCategory($id = null){
        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $dataProduct = $this->{'Data'}->getProductByCategory($id);
        $this->set(compact('dataCategory'));
        $this->set(compact('dataProduct', $this->paginate($dataProduct, ['limit'=> '3'])));
    }

    //Details Product
    public function detailsProduct($id = null){
        $dataProduct = $this->{'Data'}->getDetailsProductByID($id);
        $dataImage = $this->{'Data'}->getImageByProduct($id);
        $this->set(compact('dataProduct', 'dataImage'));
    }

}
