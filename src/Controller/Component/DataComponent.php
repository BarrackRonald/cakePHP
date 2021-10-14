<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use PhpParser\Node\Expr\Cast\Object_;


/**
 * Data component
 */
class DataComponent extends CommonComponent
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    public function initialize(array $config): void
    {
        $this->loadModel(['Users']);
        $this->loadModel('Roles');
        $this->loadModel('Products');
        $this->loadModel('Images');
        $this->loadModel('Categories');
        $this->loadModel('Orders');
        $this->loadModel('Orderdetails');
    }

    public function getSlideImage($key = null) {
        $query = $this->Images->find()
            ->where([
                'Images.del_flag' => 0,
            ])
            ->where(['Images.image_type' => 'Slider'])
            ->order('created_date DESC')
            ->limit(5)
            ->all();
        return $query;
    }

    public function getSearch($keyword){

        $query = $this->Products->find()
            ->where([
                'Products.product_name like' => '%' . $keyword . '%',
                'Products.del_flag' => 0,
            ]);
        return $query;

    }
    //Lấy password
    public function getPws($email){
        $query = $this->Users->find()
            ->select([
                'Users.id',
                'Users.password',
            ])
            ->where([
                'Users.email' => $email,
            ]);
    return $query->toArray();
    }

    public function insertUsers($dataProds, $pointAF){
        $user = [];
        $user['username'] =$dataProds['infoUser']['username'];
        $user['address'] = $dataProds['infoUser']['address'];
        $user['email'] = $dataProds['infoUser']['email'];
        $user['phonenumber'] = $dataProds['infoUser']['phonenumber'];
        $user['password'] = $dataProds['infoUser']['password'];
        $user['point_user'] = $pointAF;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:i:s');
        $user['updated_date'] = date('Y-m-d h:i:s');
        $dataUser = $this->Users->newEntity($user);

        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return $this->Users->save($dataUser);
    }

    //Check Validate cho User
    public function adduser($atribute){
        $user = [];
        $user['username'] = h(trim($atribute['fullname']));
        $user['address'] = h(trim($atribute['address']));
        $user['email'] = h(trim($atribute['email']));
        $user['phonenumber'] = h(trim($atribute['phonenumber']));



        $hashPswdObj = new DefaultPasswordHasher;
        $user['password'] = $hashPswdObj->hash($atribute['password']);
        if($atribute['password'] == ''){
            $user['password'] = '';
        }
        $user['point_user'] = 0;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:i:s');
        $user['updated_date'] = date('Y-m-d h:i:s');
        $dataUser = $this->Users->newEntity($user);
        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $user,
        ];

    }

    //Check Validate cho User không hash
    public function adduserNoHash($atribute){
        $user = [];
        $user['username'] = h(trim($atribute['fullname']));
        $user['address'] = h(trim($atribute['address']));
        $user['email'] = h(trim($atribute['email']));
        $user['phonenumber'] = h(trim($atribute['phonenumber']));
        $user['password'] = $atribute['password'];
        $user['point_user'] = 0;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:i:s');
        $user['updated_date'] = date('Y-m-d h:i:s');
        $dataUser = $this->Users->newEntity($user);
        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $user,
        ];

    }

    public function checkmail($atribute){
        $query = $this->Users->find()
            ->select([
                'Users.id',
                'Users.email',
            ])
            ->where([
                'Users.email' => $atribute['email'],
            ]);
        return $query->toArray();
    }

    public function createOrders($dataProds, $insertUser){
        $order = [];
        $order['order_name'] = 'order_'.$insertUser[0]['username'];
        $order['email'] = $insertUser[0]['email'];
        $order['phonenumber'] = $insertUser[0]['phonenumber'];
        $order['address'] = $insertUser[0]['address'];
        $order['date_order'] = date('Y-m-d h:i:s');
        $order['user_id'] = $insertUser[0]['id'];
        $order['total_point'] = $dataProds['totalAllPoint'];
        $order['total_quantity'] = $dataProds['totalquantity'];
        $order['total_amount'] = $dataProds['totalAllAmount'];
        $order['created_date'] = date('Y-m-d h:i:s');
        $order['updated_date'] = date('Y-m-d h:i:s');
        $dataOrder = $this->Orders->newEntity($order);

        if ($dataOrder->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataOrder->getErrors(),
            ];
        };
        $result = $this->Orders->save($dataOrder);

        //Add Orderdetail
        foreach ($dataProds['cart'] as $key => $product) {
            $orderDetail = [];
            $orderDetail['quantity_orderDetails'] = $product['quantity'];
            $orderDetail['amount_orderDetails'] = $product['totalAmount'];
            $orderDetail['point_orderDetail'] = $product['totalPoint'];
            $orderDetail['product_id'] = $key;
            $orderDetail['order_id'] = $result['id'];
            $orderDetail['created_date'] = date('Y-m-d h:i:s');
            $orderDetail['updated_date'] = date('Y-m-d h:i:s');
            $dataOrderDetails = $this->Orderdetails->newEntity($orderDetail);
            if ($dataOrderDetails->hasErrors()) {
                return [
                    'result' => 'invalid',
                    'data' => $dataOrderDetails->getErrors(),
                ];
            };
            $this->Orderdetails->save($dataOrderDetails);
        }

    }

    public function createOrdersNone($infoUser, $dataProds, $insertUser){
        $order = [];
        $order['order_name'] = 'order_'.$infoUser['username'];
        $order['email'] = $infoUser['email'];
        $order['phonenumber'] = $infoUser['phonenumber'];
        $order['address'] = $infoUser['address'];
        $order['date_order'] = date('Y-m-d h:i:s');
        $order['user_id'] = $insertUser['id'];
        $order['total_point'] = $dataProds['totalAllPoint'];
        $order['total_quantity'] = $dataProds['totalquantity'];
        $order['total_amount'] = $dataProds['totalAllAmount'];
        $order['created_date'] = date('Y-m-d h:i:s');
        $order['updated_date'] = date('Y-m-d h:i:s');
        $dataOrder = $this->Orders->newEntity($order);

        if ($dataOrder->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataOrder->getErrors(),
            ];
        };
        $result = $this->Orders->save($dataOrder);

        //Add Orderdetail
        foreach ($dataProds['cart'] as $key => $product) {
            $orderDetail = [];
            $orderDetail['quantity_orderDetails'] = $product['quantity'];
            $orderDetail['amount_orderDetails'] = $product['totalAmount'];
            $orderDetail['point_orderDetail'] = $product['totalPoint'];
            $orderDetail['product_id'] = $key;
            $orderDetail['order_id'] = $result['id'];
            $orderDetail['created_date'] = date('Y-m-d h:i:s');
            $orderDetail['updated_date'] = date('Y-m-d h:i:s');
            $dataOrderDetails = $this->Orderdetails->newEntity($orderDetail);
            if ($dataOrderDetails->hasErrors()) {
                return [
                    'result' => 'invalid',
                    'data' => $dataOrderDetails->getErrors(),
                ];
            };
            $this->Orderdetails->save($dataOrderDetails);
        }

    }

    public function getPointByUser($idUser){
        $query = $this->Users->find()
            ->select([
                'Users.id',
                'Users.point_user',
            ])
            ->where([
                'Users.id' => $idUser,
            ]);
        return $query->toArray();
    }

    public function updatePoint($pointAF, $idUsers){
        $query = $this->Users->query()
            ->update()
            ->set([
                'Users.point_user' => $pointAF,
            ])
            ->where([
                'Users.id' => $idUsers,
            ])
            ->execute();
    }


    public function getInfoUser($idUser){
        $query = $this->Users->find()
            ->select([
                'Users.id',
                'Users.username',
                'Users.email',
                'Users.phonenumber',
                'Users.address',
                'Users.point_user',
            ])
            ->where([
                'Users.id' => $idUser,
            ]);
        return $query->toArray();

    }

    public function getAllUser($key = null){
        $query = $this->Users->find()
        ->select([
            'Users.id',
            'Users.username',
            'Users.email',
            'Users.phonenumber',
            'Users.address',
            'Users.point_user',
            'Users.role_id',
            'Users.address',
            'Roles.role_name'
        ])
        ->join([
            'table' => 'roles',
            'alias' => 'roles' ,
            'type' => 'left',
            'conditions' => ['Users.role_id = Roles.id']
        ]);
        return $query->toArray();
    }

    public function getProductByID($product_id){
        $query = $this->Products->find()
            ->select([
                'Products.id',
                'Products.product_name',
                'Products.amount_product',
                'Products.point_product',
            ])
            ->where([
                'products.id' => $product_id,
            ])
            ->contain(['Images'=> function ($q) {
                return $q->order('Images.updated_date DESC');
                }])

            ;
        return $query->toArray();
    }

    public function getNewsProduct($key = null){
        $query = $this->Products->find()
        ->where([
            'Products.del_flag' => 0,
        ])
        ->order('created_date DESC')
        ->contain(['Images'=> function ($q) {
            return $q->order('Images.updated_date DESC');
            }])
        ->all();
        return $query;
    }

    public function getCategory($key = null){
        $query = $this->Categories->find()
        ->limit(5)
        ->where([
            'Categories.del_flag' => 0,
        ])
        ->all();
        return $query;
    }

    public function getAllProducts($key = null){
        $query = $this->Products->find()
            ->order('created_date DESC')
            ->limit(2)
            ->contain(['Images'=> function ($q) {
                return $q->order('Images.updated_date DESC');
                }])
            ->all();
        return $query;
    }

    public function createUsers($atribute){
        $user = [];
        $user['username'] = $atribute['username'];
        $user['password'] = $atribute['password'];
        $user['role_id'] = 1;
        $user['avatar'] =  'default.png';
        $user['email'] =  'hoan@gmail.com';
        $user['phonenumber'] =  0000000000;
        $user['point_user'] =  111;

        $ac = $this->Users->newEntity($user);
        $result = $this->Users->save($ac);
        if ($ac->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $ac->getErrors(),
            ];
        }
        return [
            'result' => 'success',
            'data' =>  $result
        ];
    }

    public function updateUserByID($atribute, $idUser){
        $query = $this->Users->query()
        ->update()
        ->set([
            'Users.username' => $atribute['username'],
            'Users.email' => $atribute['email'],
            'Users.phonenumber' => $atribute['phonenumber'],
            'Users.address' => $atribute['address'],
        ])
        ->where([
            'Users.id' => $idUser,
        ])
        ->execute();
    }

    public function checklogin($atribute){
        $query = $this->Users->query()
        ->Where([
            'Users.email'=> $atribute['email'],
            'Users.del_flag'=> 0,

        ]);
        return $query->toArray();
    }

    public function getProductByCategory($id){
        $query = $this->Products->query()
        ->Where([
            'Products.category_id'=> $id,
            'Products.del_flag'=> 0,
        ])
        ->contain(['Images'=> function ($q) {
            return $q->order('Images.updated_date DESC');
        }]);
        return $query;
    }

    public function getDetailsProductByID($id){
        $query= $this->Products->query()
        ->select([
            'Products.id',
            'Products.product_name',
            'Products.description',
            'Products.amount_product',
            'Products.point_product',
            'Products.category_id',
            'Categories.category_name'
        ])
        ->join([
            'table' => 'categories',
            'alias' => 'categories' ,
            'type' => 'left',
            'conditions' => ['Products.category_id = Categories.id']
        ])
        ->Where([
            'Products.id'=> $id,
            'Products.del_flag'=> 0,
        ])
        ->contain(['Images'=> function ($q) {
            return $q->order('Images.updated_date DESC');
        }])
        ;
        return $query->toArray();
    }

    public function getImageByProduct($id){
        $query= $this->Images->query()
        ->Where([
            'Images.product_id'=> $id,
            'Images.del_flag'=> 0,
            'Images.image_type'=> 'Banner',
        ])
        ->order('Images.updated_date DESC')
        ->limit(5);
        return $query->toArray();
    }
}
