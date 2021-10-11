<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Auth\DefaultPasswordHasher;

/**
 * CRUD component
 */
class CRUDComponent extends CommonComponent
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

    public function getUserByID($id){
        $query = $this->Users->find()
            ->where([
                'Users.id' => $id,
            ]);
        return $query->toArray();
    }

    public function getAllRoles($key = null){
        $query = $this->Roles->find()
        ->select([
            'Roles.id',
            'Roles.role_name',
        ]);
        return $query->toArray();
    }

    public function adduser($atribute){
        $user = [];
        $user['username'] = h($atribute['username']);
        $user['address'] = h($atribute['address']);
        $user['email'] = h($atribute['email']);
        $user['phonenumber'] = $atribute['phonenumber'];
        $hashPswdObj = new DefaultPasswordHasher;
        $user['password'] = $hashPswdObj->hash($atribute['password']);
        if($atribute['password'] == ''){
            $user['password'] = '';
        }
        $user['point_user'] = 0;
        $user['role_id'] = $atribute['role_id'];
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
            'data' => $this->Users->save($dataUser),
        ];
    }

    public function getUser($key = null){
        $query = $this->Users->find()
        ->select([
            'Users.id',
            'Users.username',
            'Users.email',
            'Users.phonenumber',
            'Users.address',
            'Users.point_user',
            'Users.role_id',
            'Users.del_flag',
            'Users.address',
            'Roles.role_name'
        ])
        ->join([
            'table' => 'roles',
            'alias' => 'roles' ,
            'type' => 'left',
            'conditions' => ['Users.role_id = Roles.id']
        ])
        ->where([
            // 'Users.del_flag' => 0,
            // 'OR' => [['Users.role_id' => 1], ['Users.role_id' => 3]],
        ])
        ->order('Users.created_date DESC')
        ;
        return $query;
    }

    // Categories
    public function getAllCategory($key = null){
        $query = $this->Categories->find()
        ->select([
            'Categories.id',
            'Categories.category_name',
            'Categories.del_flag',
        ])
        ->where([
            'Categories.del_flag' => 0,
        ]);
        return $query;
    }

    //Check Category ID
    public function checkIDCategory($id){
        $query = $this->Categories->find()
        ->where([
            'Categories.id' => $id,
            'Categories.del_flag' => 0,
        ]);
        return $query->toArray();
    }

    //Check Order ID
    public function checkIDOrder($id){
        $query = $this->Orders->find()
        ->where([
            'Orders.id' => $id
        ]);
        return $query->toArray();
    }

    //Check User ID
    public function checkIDUser($id){
        $query = $this->Users->find()
        ->where([
            'Users.id' => $id,
            'Users.del_flag' => 0,
        ]);
        return $query->toArray();
    }

    //Check Product ID
    public function checkIDProduct($id){
        $query = $this->Products->find()
        ->where([
            'Products.id' => $id,
            'Products.del_flag' => 0,
        ]);
        return $query->toArray();
    }

    //Check Order Details ID
    public function checkIDOrderDetails($id){
        $query = $this->Orderdetails->find()
        ->where([
            'Orderdetails.id' => $id,
        ]);
        return $query->toArray();
    }

    public function getCategoryByID($id){
        $query = $this->Categories->find()
            ->where([
                'Categories.id' => $id,
            ]);
        return $query->toArray();
    }

    public function addCategory($atribute){
        $category = [];
        $category['category_name'] = h($atribute['category_name']);
        $category['created_date'] = date('Y-m-d h:m:s');
        $category['updated_date'] = date('Y-m-d h:m:s');
        $dataCategory = $this->Categories->newEntity($category);

        if ($dataCategory->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataCategory->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $this->Categories->save($dataCategory),
        ];
    }

    public function checkProductByCategory($atribute){
        $idCategory = $atribute['id'];
        $query = $this->Products->find()
            ->where([
                'Products.category_id' => $idCategory,
            ]);
        return $query->toArray();
    }

    //Products
    public function getAllProduct($key = null){
        $query = $this->Products->find()
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
        ->where([
            'Products.del_flag' => 0,
        ])
        ->order('Products.created_date DESC')
        ->contain(['Images']);
        return $query;
    }

    public function addproduct($atribute){
        $product = [];
        $product['product_name'] = h($atribute['product_name']);
        $product['description'] = h($atribute['description']);
        $product['amount_product'] = h($atribute['amount_product']);
        $product['point_product'] = h($atribute['point_product']);
        $product['category_id'] = h($atribute['category_id']);
        $product['created_date'] = date('Y-m-d h:m:s');
        $product['updated_date'] = date('Y-m-d h:m:s');
        $dataProduct = $this->Products->newEntity($product);

        if ($dataProduct->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataProduct->getErrors(),
            ];
        };

        //Add Image vào Table Image
        $result = $this->Products->save($dataProduct);
        $images = $this->Images->newEmptyEntity();

        $image = $atribute['uploadfile'];
        $name = $image->getClientFilename();
        $targetPath = WWW_ROOT.'img'.DS.$name;

        if($name){
            $image->moveTo($targetPath);
            $images->image = '../../img/'.$name;
        }

        $images->image_name = 'img'.$atribute['product_name'] ;
        $images->image_type = 'Banner';
        $images->user_id = 1;
        $images->product_id = $result['id'];
        $images->created_date = date('Y-m-d h:m:s');
        $images->updated_date = date('Y-m-d h:m:s');

        $this->Images->save($images);

        return [
            'result' => 'success',
            'data' => $this->Products->save($dataProduct),
        ];

    }

    public function getProductByID($id){
        $query = $this->Products->find()
            ->where([
                'Products.id' => $id,
            ])
            ->contain(['Images']);
        return $query->toArray();
    }

    //Search
    public function getSearch($key){

        $query = $this->Products->find()
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
        ->order('Products.created_date DESC')
        ->contain(['Images'])
        ->where([
                'Products.product_name like' => '%'. $key .'%',
                'Products.del_flag' => 0
        ]);
        return $query;
    }

    //Orders
    public function getAllOrder($key = null){
        $query = $this->Orders->find()
        ->select([
            'Orders.id',
            'Orders.email',
            'Orders.phonenumber',
            'Orders.address',
            'Orders.total_point',
            'Orders.total_quantity',
            'Orders.total_amount',
            'Orders.status',
            'Orders.user_id',
            'Users.username'
        ])
        ->join([
            'table' => 'users',
            'alias' => 'users' ,
            'type' => 'left',
            'conditions' => ['Orders.user_id = Users.id']
        ])
        ->order('Orders.created_date DESC');
        return $query;
    }

    //Lấy chi tiết đơn hàng
    public function getOrderDetailsByID($id){
        $query = $this->Orderdetails->find()
        ->select([
            'Orderdetails.id',
            'Orderdetails.quantity_orderDetails',
            'Orderdetails.amount_orderDetails',
            'Orderdetails.point_orderDetail',
            'Orderdetails.product_id',
            'Products.product_name',
            'Orderdetails.order_id',
        ])
        ->join([
            'table' => 'orders',
            'alias' => 'orders' ,
            'type' => 'left',
            'conditions' => ['Orderdetails.order_id = Orders.id']
        ])
        ->join([
            'table' => 'products',
            'alias' => 'products' ,
            'type' => 'left',
            'conditions' => ['Orderdetails.product_id = Products.id']
        ])
        ->where([
            'Orderdetails.order_id' => $id,
        ]);
        return $query;
    }


    //Search Orders
    public function getSearchOrder($key){

        $query = $this->Orders->find()
        ->select([
            'Orders.id',
            'Orders.email',
            'Orders.phonenumber',
            'Orders.address',
            'Orders.total_point',
            'Orders.total_quantity',
            'Orders.total_amount',
            'Orders.status',
            'Orders.user_id',
            'Users.username'
        ])
        ->join([
            'table' => 'users',
            'alias' => 'users' ,
            'type' => 'left',
            'conditions' => ['Orders.user_id = Users.id']
        ])
        ->order('Orders.created_date DESC')
        ->where([
            'OR' => [['Users.username like' => '%'. $key .'%'], ['Orders.email like' => '%'. $key .'%']]
        ]);
        return $query;
    }

    public function addorder($atribute){
        $product = [];
        $product['product_name'] = h($atribute['product_name']);
        $product['description'] = h($atribute['description']);
        $product['amount_product'] = h($atribute['amount_product']);
        $product['point_product'] = h($atribute['point_product']);
        $product['category_id'] = h($atribute['category_id']);
        $product['created_date'] = date('Y-m-d h:m:s');
        $product['updated_date'] = date('Y-m-d h:m:s');
        $dataProduct = $this->Products->newEntity($product);
        // dd($dataProduct);

        if ($dataProduct->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataProduct->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $this->Products->save($dataProduct),
        ];
    }

    public function getOrderByID($id){
        $query = $this->Orders->find()
        ->select([
            'Orders.id',
            'Orders.email',
            'Orders.phonenumber',
            'Orders.address',
            'Orders.total_point',
            'Orders.total_quantity',
            'Orders.total_amount',
            'Orders.status',
            'Orders.user_id',
            'Users.username'
        ])
        ->join([
            'table' => 'users',
            'alias' => 'users' ,
            'type' => 'left',
            'conditions' => ['Orders.user_id = Users.id']
        ])
        ->where([
            'Orders.id' => $id,
        ]);

        return $query->toArray();
    }

    public function register($atribute){
        $user = [];
        $user['username'] = h($atribute['fullname']);
        $user['address'] = h($atribute['address']);
        $user['email'] = h($atribute['email']);
        $user['phonenumber'] = h($atribute['phonenumber']);
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
        // dd($dataUser);
        if ($dataUser->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataUser->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $dataUser,
        ];
    }

    public function getUsersByEmail($email){
        $query = $this->Users->find()
        ->where([
            'email' => $email,
        ])
        ->first();
        return $query;
    }

    public function getUsersByEmailArr($email){
        $query = $this->Users->find()
        ->where([
            'email' => $email,
        ]);
        return $query->toArray();
    }

    public function checkDelFlagByEmail($email){
        $query = $this->Users->find()
        ->select([
            'Users.del_flag'
        ])
        ->where([
            'Users.email' => $email,
            'Users.del_flag' => 1
        ]);
        return $query->toArray();
    }

    //Images
    public function getAllImage($key = null){
        $query = $this->Images->find()
        ->select([
            'Images.id',
            'Images.image_name',
            'Images.image_type',
            'Images.image_type',
            'Images.image',
            'Images.user_id',
            'Images.del_flag',
            'Images.product_id',
            'Products.product_name'
        ])
        ->join([
            'table' => 'products',
            'alias' => 'products' ,
            'type' => 'left',
            'conditions' => ['Images.product_id = Products.id']
        ])
        ->where([
            'Images.del_flag' => 0,
        ])
        ->order('Images.created_date DESC');
        return $query;
    }

    public function addimage($atribute){
        $image = [];
        $image['image_name'] = h($atribute['product_name']);
        $image['description'] = h($atribute['description']);
        $image['amount_product'] = h($atribute['amount_product']);
        $image['point_product'] = h($atribute['point_product']);
        $image['category_id'] = h($atribute['category_id']);
        $image['created_date'] = date('Y-m-d h:m:s');
        $image['updated_date'] = date('Y-m-d h:m:s');
        $dataImage = $this->Images->newEntity($image);
        // dd($dataProduct);

        if ($dataImage->hasErrors()) {
            return [
                'result' => 'invalid',
                'data' => $dataImage->getErrors(),
            ];
        };
        return [
            'result' => 'success',
            'data' => $this->Products->save($dataImage),
        ];
    }

    public function getimageByID($id){
        $query = $this->Products->find()
            ->where([
                'Products.id' => $id,
            ]);
        return $query->toArray();
    }

}
