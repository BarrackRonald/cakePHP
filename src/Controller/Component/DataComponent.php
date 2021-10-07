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
        // dd($atribute);
        $user = [];
        $user['username'] =$dataProds['infoUser']['username'];
        $user['address'] = $dataProds['infoUser']['address'];
        $user['email'] = $dataProds['infoUser']['email'];
        $user['phonenumber'] = $dataProds['infoUser']['phonenumber'];
        $user['password'] = $dataProds['infoUser']['password'];
        $user['point_user'] = $pointAF;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:m:s');
        $user['updated_date'] = date('Y-m-d h:m:s');
        $dataUser = $this->Users->newEntity($user);

        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return $this->Users->save($dataUser);
    }

    public function adduser($atribute){
        $user = [];
        $user['username'] = $atribute['fullname'];
        $user['address'] = $atribute['address'];
        $user['email'] = $atribute['email'];
        $user['phonenumber'] = $atribute['phonenumber'];
        $hashPswdObj = new DefaultPasswordHasher;
        $user['password'] = $hashPswdObj->hash($atribute['password']);
        if($atribute['password'] == ''){
            $user['password'] = '';
        }
        $user['point_user'] = 0;
        $user['role_id'] = 1;
        $user['avatar'] = 'none.jbg';
        $user['created_date'] = date('Y-m-d h:m:s');
        $user['updated_date'] = date('Y-m-d h:m:s');
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

    public function createOrders($atribute, $dataProds, $insertUser){
        $order = [];
        $order['order_name'] = 'order_'.$atribute['fullname'];
        $order['email'] = $atribute['email'];
        $order['phonenumber'] = $atribute['phonenumber'];
        $order['address'] = $atribute['address'];
        $order['date_order'] = date('Y-m-d h:m:s');
        if(isset($atribute['idUser'])){
            $order['user_id'] = $atribute['idUser'];
        }else{
            $order['user_id'] = $insertUser['id'];
        }
        $order['total_point'] = $atribute['totalAllPoint'];
        $order['total_quantity'] = $atribute['totalQuantity'];
        $order['total_amount'] = $atribute['totalAllAmount'];
        $order['created_date'] = date('Y-m-d h:m:s');
        $order['updated_date'] = date('Y-m-d h:m:s');
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
            $orderDetail['created_date'] = date('Y-m-d h:m:s');
            $orderDetail['updated_date'] = date('Y-m-d h:m:s');
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
                'Images.image'
            ])
            ->join([
                'table' => 'images',
                'alias' => 'Images' ,
                'type' => 'left',
                'conditions' => ['Products.id = Images.product_id']
            ])
            ->where([
                'products.id' => $product_id,
            ])

            ;
        return $query->toArray();
    }

    public function getNewsProduct($key = null){
        $query = $this->Products->find()
        ->where([
            'Products.del_flag' => 0,
        ])
        ->order('created_date DESC')
        ->contain(['Images'])
        ->all();
        return $query;
    }

    public function getCategory($key = null){
        $query = $this->Categories->find()
            ->all();
        return $query;
    }

    public function getAllProducts($key = null){
        $query = $this->Products->find()
            ->order('created_date DESC')
            ->limit(2)
            ->contain(['Images'])
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
        dd($ac->hasErrors());
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

}
