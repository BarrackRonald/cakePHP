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
        $session = $this->request->getSession();

        if($session->check('idUser')){
            $idUsers = $session->read('idUser');
            $dataUser = $this->{'Data'}->getInfoUser($idUsers);
            $this->set(compact('dataUser'));
        }
        $this->set(compact('dataProducts', 'dataSlideImages', 'dataNewsProducts', 'dataCategories'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();
        if($session->check('error')){
            $session->delete('error');
        }
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
                }else {
                    $result = $this->{'Data'}->checklogin($insertUser);
                    if(count($result) > 0){
                        $idUser = $result[0]['id'];
                        $username = $result[0]['username'];
                        $session = $this->request->getSession();
                        $session->write('idUser', $idUser);
                        $session->write('username', $username);

                        //Check quyền gắn cờ
                        if($result[0]['role_id'] == 1){
                                $flag = 1;
                        }elseif ($result[0]['role_id'] == 2) {
                                $flag = 2;
                            }else{
                                $flag = 3;
                            }
                            $session->write('flag', $flag);
                    } else {
                            $this->Flash->error('Hệ thống đăng ký tài khoản thất bại. Vui lòng đặt hàng lại.');
                            $this->redirect(['action' => 'index']);
                    }
                }
                //  Insert Order
                 $insertOrder = $this->{'Data'}->createOrdersNone($infoUser, $dataProds, $insertUser);
                 if(!$insertOrder['result'] == "invalid")
                 {
                     $to = $infoUser['email'];
                     $toAdmin = 'tienphamvan2005@gmail.com';
                     $subject = 'Mail Confirm Order';
                     $message = '
                     <!DOCTYPE html>
                     <html>
                     <head>
                         <title></title>
                         <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                         <meta name="viewport" content="width=device-width, initial-scale=1">
                         <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                         <style type="text/css">
                             body,
                             table,
                             td,
                             a {
                                 -webkit-text-size-adjust: 100%;
                                 -ms-text-size-adjust: 100%;
                             }
                             table,
                             td {
                                 mso-table-lspace: 0pt;
                                 mso-table-rspace: 0pt;
                             }
                             img {
                                 -ms-interpolation-mode: bicubic;
                             }
                             img {
                                 border: 0;
                                 height: auto;
                                 line-height: 100%;
                                 outline: none;
                                 text-decoration: none;
                             }
                             table {
                                 border-collapse: collapse !important;
                             }
                             body {
                                 height: 100% !important;
                                 margin: 0 !important;
                                 padding: 0 !important;
                                 width: 100% !important;
                             }
                             a[x-apple-data-detectors] {
                                 color: inherit !important;
                                 text-decoration: none !important;
                                 font-size: inherit !important;
                                 font-family: inherit !important;
                                 font-weight: inherit !important;
                                 line-height: inherit !important;
                             }
                             @media screen and (max-width: 480px) {
                                 .mobile-hide {
                                     display: none !important;
                                 }
                                 .mobile-center {
                                     text-align: center !important;
                                 }
                             }
                             div[style*="margin: 16px 0;"] {
                                 margin: 0 !important;
                             }
                         </style>
                     <body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
                         <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
                             Bạn đã đặt hàng thành công trên Website: Vertu.vn
                         </div>
                         <table border="0" cellpadding="0" cellspacing="0" width="100%">
                             <tr>
                                 <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                                     <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                         <tr>
                                             <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#ee5057">
                                                 <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                                     <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                         <tr>
                                                             <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                                                 <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">VERTU</h1>
                                                             </td>
                                                         </tr>
                                                     </table>
                                                 </div>
                                                 <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;" class="mobile-hide">
                                                     <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                         <tr>
                                                             <td align="right" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; line-height: 48px;">
                                                                 <table cellspacing="0" cellpadding="0" border="0" align="right">
                                                                     <tr>
                                                                         <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400;">
                                                                             <p style="font-size: 18px; font-weight: 400; margin: 0; color: #ffffff;"><a href="https://test.com/" target="_blank" style="color: #ffffff; text-decoration: none;">Shop &nbsp;</a></p>
                                                                         </td>
                                                                         <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;"> <a href="https://test.com/" target="_blank" style="color: #ffffff; text-decoration: none;"><img src="https://img.icons8.com/color/48/000000/small-business.png" width="27" height="23" style="display: block; border: 0px;" /></a> </td>
                                                                     </tr>
                                                                 </table>
                                                             </td>
                                                         </tr>
                                                     </table>
                                                 </div>
                                             </td>
                                         </tr>
                                         <tr>
                                             <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                                 <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                                     <tr>
                                                         <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                                             <h2 style="font-size: 30px; font-weight: 700; line-height: 36px; color: #333333; margin: 0;"> Cảm ơn '.$infoUser['username'].' đã đặt hàng tại VerTu.vn! </h2>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                                             <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> VerTu.vn rất hân hạnh đồng hành cùng bạn. Thông tin đơn hàng của bạn như sau: </p>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="left" style="padding-top: 20px;">
                                                             <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                                 <tr>
                                                                     <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Thông tin Khách hàng </td>
                                                                     <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">  </td>
                                                                 <tr>
                                                                     <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Khách hàng:  </td>
                                                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.$infoUser['username'].' </td>
                                                                 </tr>
                                                                 <tr>
                                                                     <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Số điện thoại:  </td>
                                                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.$infoUser['phonenumber'].' </td>
                                                                 </tr>

                                                                 <tr>
                                                                     <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Mã Đơn hàng </td>
                                                                     <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;"> #'.$insertOrder["id"].' </td>
                                                                 </tr>';
                                                                 foreach($dataProds['cart'] as $value) {
                                                                     $product .= '<tr>
                                                                             <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">'.$value["name"].' × '.$value["quantity"].'</td>
                                                                             <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> $'.number_format($value["totalAmount"]).' </td>
                                                                         </tr>'." \r\n";
                                                                     }
                                                                 $message .=''.$product.'
                                                             </table>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="left" style="padding-top: 20px;">
                                                             <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                                <tr>
                                                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Point </td>
                                                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">  </td>
                                                                <tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point của đơn hàng:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.number_format($dataProds['totalAllPoint']).' point </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point của bạn hiện có:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> 0 point </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point sau khi mua hàng:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.number_format($dataProds['totalAllPoint']).' point </td>
                                                                </tr>
                                                                 <tr>
                                                                     <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; "> Tổng Point nhận:  </td>
                                                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; "> '.number_format($dataProds['totalAllPoint']).' </td>
                                                                 </tr>
                                                                 <tr>
                                                                     <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> Tổng thanh toán:  </td>
                                                                     <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> $'.number_format($dataProds['totalAllAmount']).' </td>
                                                                 </tr>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                         <tr>
                                             <td align="center" height="100%" valign="top" width="100%" style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                                 <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                                                     <tr>
                                                         <td align="center" valign="top" style="font-size:0;">
                                                             <div style="display:inline-block; max-width:100%; min-width:240px; vertical-align:top; width:100%;">
                                                                 <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                     <tr>
                                                                         <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                                             <p style="font-weight: 700;">Địa chỉ giao hàng</p>
                                                                             <p>'.$infoUser['address'].'</p>
                                                                         </td>
                                                                     </tr>
                                                                 </table>
                                                             </div>
                                                             <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                                                 <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                                     <tr>
                                                                         <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                                         </td>
                                                                     </tr>
                                                                 </table>
                                                             </div>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                         <tr>
                                             <td align="center" style=" padding: 35px; background-color: #ff7361;" bgcolor="#1b9ba3">
                                                 <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                                     <tr>
                                                         <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                                             <h2 style="font-size: 24px; font-weight: 700; line-height: 30px; color: #ffffff; margin: 0;"> VerTu.vn đang có nhiều ưu đãi dành cho bạn. Truy cập ngay!!! </h2>
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <td align="center" style="padding: 25px 0 15px 0;">
                                                             <table border="0" cellspacing="0" cellpadding="0">
                                                                 <tr>
                                                                     <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7"> <a href="https://test.com/" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #F44336; padding: 15px 30px; border: 1px solid #F44336; display: block;">Truy Cập</a> </td>
                                                                 </tr>
                                                             </table>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             </td>
                                         </tr>
                                     </table>
                                 </td>
                             </tr>
                         </table>
                     </body>
                     </html>';
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
           $this->request->getData();
           $session = $this->request->getSession();
           $product = null;

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
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title></title>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                        <style type="text/css">
                            body,
                            table,
                            td,
                            a {
                                -webkit-text-size-adjust: 100%;
                                -ms-text-size-adjust: 100%;
                            }
                            table,
                            td {
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                            }
                            img {
                                -ms-interpolation-mode: bicubic;
                            }
                            img {
                                border: 0;
                                height: auto;
                                line-height: 100%;
                                outline: none;
                                text-decoration: none;
                            }
                            table {
                                border-collapse: collapse !important;
                            }
                            body {
                                height: 100% !important;
                                margin: 0 !important;
                                padding: 0 !important;
                                width: 100% !important;
                            }
                            a[x-apple-data-detectors] {
                                color: inherit !important;
                                text-decoration: none !important;
                                font-size: inherit !important;
                                font-family: inherit !important;
                                font-weight: inherit !important;
                                line-height: inherit !important;
                            }
                            @media screen and (max-width: 480px) {
                                .mobile-hide {
                                    display: none !important;
                                }
                                .mobile-center {
                                    text-align: center !important;
                                }
                            }
                            div[style*="margin: 16px 0;"] {
                                margin: 0 !important;
                            }
                        </style>
                    <body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
                        <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
                            Bạn đã đặt hàng thành công trên Website: Vertu.vn
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                        <tr>
                                            <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#ee5057">
                                                <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                        <tr>
                                                            <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                                                <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">VERTU</h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;" class="mobile-hide">
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                        <tr>
                                                            <td align="right" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; line-height: 48px;">
                                                                <table cellspacing="0" cellpadding="0" border="0" align="right">
                                                                    <tr>
                                                                        <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400;">
                                                                            <p style="font-size: 18px; font-weight: 400; margin: 0; color: #ffffff;"><a href="https://test.com/" target="_blank" style="color: #ffffff; text-decoration: none;">Shop &nbsp;</a></p>
                                                                        </td>
                                                                        <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;"> <a href="https://test.com/" target="_blank" style="color: #ffffff; text-decoration: none;"><img src="https://img.icons8.com/color/48/000000/small-business.png" width="27" height="23" style="display: block; border: 0px;" /></a> </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                                    <tr>
                                                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                                            <h2 style="font-size: 30px; font-weight: 700; line-height: 36px; color: #333333; margin: 0;"> Cảm ơn '.$dataUser[0]["username"].' đã đặt hàng tại VerTu.vn! </h2>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                                            <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> VerTu.vn rất hân hạnh đồng hành cùng bạn. Thông tin đơn hàng của bạn như sau: </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="padding-top: 20px;">
                                                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                                <tr>
                                                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Thông tin Khách hàng </td>
                                                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">  </td>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Khách hàng:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.$dataUser[0]["username"].' </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Số điện thoại:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.$dataUser[0]["phonenumber"].' </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Mã Đơn hàng </td>
                                                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;"> #'.$result["id"].' </td>
                                                                </tr>';
                                                                foreach($dataProds['cart'] as $value) {
                                                                    $product .= '<tr>
                                                                            <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">'.$value["name"].' × '.$value["quantity"].'</td>
                                                                            <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> $'.number_format($value["totalAmount"]).' </td>
                                                                        </tr>'." \r\n";
                                                                    }
                                                                $message .=''.$product.'
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="padding-top: 20px;">
                                                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                                <tr>
                                                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;"> Point </td>
                                                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">  </td>
                                                                <tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point của đơn hàng:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.number_format($dataProds['totalAllPoint']).' point </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point của bạn hiện có:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.number_format($dataUser[0]['point_user']).' point </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">Point sau khi mua hàng:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;"> '.number_format($pointAF).' point </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> Tổng thanh toán:  </td>
                                                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> $'.number_format($dataProds['totalAllAmount']).' </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" height="100%" valign="top" width="100%" style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
                                                    <tr>
                                                        <td align="center" valign="top" style="font-size:0;">
                                                            <div style="display:inline-block; max-width:100%; min-width:240px; vertical-align:top; width:100%;">
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                                            <p style="font-weight: 700;">Địa chỉ giao hàng</p>
                                                                            <p>'.$dataUser[0]['address'].'</p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                                                    <tr>
                                                                        <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style=" padding: 35px; background-color: #ff7361;" bgcolor="#1b9ba3">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                                                    <tr>
                                                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                                            <h2 style="font-size: 24px; font-weight: 700; line-height: 30px; color: #ffffff; margin: 0;"> VerTu.vn đang có nhiều ưu đãi dành cho bạn. Truy cập ngay!!! </h2>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="padding: 25px 0 15px 0;">
                                                            <table border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td align="center" style="border-radius: 5px;" bgcolor="#66b3b7"> <a href="https://test.com/" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #F44336; padding: 15px 30px; border: 1px solid #F44336; display: block;">Truy Cập</a> </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                    </html>';
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
            if($session->check('error')){
                $session->delete('error');
            }

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

        //Check URL_ID
        if(!is_numeric($id)){
            $this->Flash->error(__('Người dùng không tồn tại.'));
            return $this->redirect(['action' => 'myaccount']);
        }else{
            $checkUserID = $this->{'CRUD'}->checkIDUser($id);
            if(count($checkUserID) < 1){
                $this->Flash->error(__('Người dùng không tồn tại.'));
                return $this->redirect(['action' => 'myaccount']);
            }
        }

        $dataUser = $this->{'CRUD'}->getUserByID($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $atribute = $this->request->getData();
            if(
                $atribute['username'] == $dataUser[0]['username'] &&
                $atribute['phonenumber'] == $dataUser[0]['phonenumber'] &&
                $atribute['address'] == $dataUser[0]['address']
            ){
                $this->Flash->error(__('Tài khoản không có sự thay đổi.'));
            }else {
                $user = $this->Users->patchEntity($dataUser[0], $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Tài khoản đã được cập nhật thành công.'));
                    return $this->redirect(['action' => 'myaccount']);
                }
                $this->Flash->error(__('Tài khoản chưa được cập nhật. Vui lòng thử lại.'));
            }
        }

        $this->set(compact('dataUser'));
    }


    //View product by Category
    public function viewProductByCategory($id = null){
        //Check URL_ID
        if(!is_numeric($id)){
            $this->Flash->error(__('Danh mục không tồn tại.'));
            return $this->redirect(['action' => 'index']);
        }else{
            $checkCategoryID = $this->{'CRUD'}->checkIDCategory($id);
            if(count($checkCategoryID) < 1){
                $this->Flash->error(__('Danh mục không tồn tại.'));
                return $this->redirect(['action' => 'index']);
            }
        }

        $dataCategory = $this->{'CRUD'}->getCategoryByID($id);
        $dataProduct = $this->{'Data'}->getProductByCategory($id);
        $this->set(compact('dataCategory'));
        $this->set(compact('dataProduct', $this->paginate($dataProduct, ['limit'=> '3'])));
    }

    //Details Product
    public function detailsProduct($id = null){
        //Check URL_ID
        if(!is_numeric($id)){
            $this->Flash->error(__('Sản phẩm không tồn tại.'));
            return $this->redirect(['action' => 'index']);
        }else{
            $checkProductID = $this->{'CRUD'}->checkIDProduct($id);
            if(count($checkProductID) < 1){
                $this->Flash->error(__('Sản phẩm không tồn tại.'));
                return $this->redirect(['action' => 'index']);
            }
        }

        $dataProduct = $this->{'Data'}->getDetailsProductByID($id);
        $dataImage = $this->{'Data'}->getImageByProduct($id);
        $idCategory = $dataProduct[0]['category_id'];
        $idProduct = $dataProduct[0]['id'];
        $dataProductByCategory = $this->{'Data'}->similarProduct($idCategory, $idProduct);
        $this->set(compact('dataProduct', 'dataImage', 'dataProductByCategory'));
    }

    //History Product
    public function historyOrders(){
        $session = $this->request->getSession();
        $idUsers = $session->read('idUser');
        if($session->check('idUser')){
            $dataUser = $this->{'Data'}->getInfoUser($idUsers);
            $this->set(compact('dataUser'));
        }
        $dataOrders = $this->{'Data'}->getOrdersByUser($idUsers);
        $this->set(compact('dataOrders'));
    }
}
