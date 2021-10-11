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
        $this->loadComponent('CRUD');
        $this->loadModel("Users");
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //Viết ở beforeRender
        $dataCategories = $this->{'Data'}->getCategory();
        $dataProducts = $this->{'Data'}->getAllProducts();
        $dataSlideImages = $this->{'Data'}->getSlideImage();
        $dataNewsProducts = $this->{'Data'}->getNewsProduct();

        $this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts', 'dataCategories'));
    }

    public function billOrder(){
        if($this->request->is('post')){
            $session = $this->request->getSession();
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
                // echo '<script> if (confirm("Bạn có muốn đăng nhập để đặt hàng không: ")) {
                //     window.location.assign("/login");
                //    } </script>';
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
            // dd($dataUser['data']);
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
            $atribute = $this->request->getData();
            $session = $this->request->getSession();
            $product = null;


            //Check Dữ liệu bắt buộc không đổi
            if($session->check('cartData')){
               $cartData = $session->read('cartData');
               $infoUser = $cartData['infoUser'];
            //    dd($cartData);
               if(!(
                   $cartData['totalAllAmount'] == $atribute['totalAllAmount'] &&
                   $cartData['totalAllPoint'] == $atribute['totalAllPoint'] &&
                   $cartData['totalquantity'] == $atribute['totalQuantity'] &&
                   $infoUser['username'] == $atribute['fullname'] &&
                   $infoUser['address'] == $atribute['address'] &&
                   $infoUser['email'] == $atribute['email'] &&
                   $infoUser['phonenumber'] == $atribute['phonenumber']
               )){
                $this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận Đặt Hàng!!!'));
                return $this->redirect(['action' => 'adduser']);

               }
            }

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
                     $toAdmin = 'tienphamvan2005@gmail.com';
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
                    $toAdmin = 'tienphamvan2005@gmail.com';
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
                  'image'=> $data[0]['Images']["image"],
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
            $user = $this->Users->patchEntity($dataUser[0], $this->request->getData());
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

}
