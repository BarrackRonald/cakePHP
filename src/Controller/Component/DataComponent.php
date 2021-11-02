<?php

declare(strict_types=1);

namespace App\Controller\Component;
use Cake\Auth\DefaultPasswordHasher;

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

	//Lấy ảnh có Type là Slide
	public function getSlideImage()
	{
		$query = $this->Images->find()
			->where([
				'Images.del_flag' => 0,
				'Images.image_type' => 'Slider'
			])
			->order('created_date DESC')
			->limit(5)
			->all();
		return $query;
	}

	//Search Products
	public function getSearch($keyword)
	{
		$query = $this->Products->find()
			->where([
				'Products.product_name like' => '%' . $keyword . '%',
				'Products.del_flag' => 0,
			]);
		return $query;
	}
	//Lấy password
	public function getPws($email)
	{
		$query = $this->Users->find()
			->select([
				'Users.id',
				'Users.password',
			])
			->where([
				'Users.email' => $email,
				'Users.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Insert Users
	public function insertUsers($dataProds, $pointAF)
	{
		$user = [];
		$user['username'] = $dataProds['infoUser']['username'];
		$user['address'] = $dataProds['infoUser']['address'];
		$user['email'] = $dataProds['infoUser']['email'];
		$user['phonenumber'] = $dataProds['infoUser']['phonenumber'];
		$user['password'] = $dataProds['infoUser']['password'];
		$user['point_user'] = $pointAF;
		$user['role_id'] = 1;
		$user['avatar'] = 'none.jbg';
		$user['created_date'] = date('Y-m-d h:i:s');
		$user['updated_date'] = date('Y-m-d h:i:s');
		$dataUser = $this->Users->newEntity($user, ['validate' => 'Custom']);

		if ($dataUser->hasErrors()) {
			return [
				'result' => 'invalid',
				'data' => $dataUser->getErrors(),
			];
		};
		return $this->Users->save($dataUser);
	}

	//Check Validate cho User
	public function addUser($atribute)
	{
		$user = [];
		$user['username'] = h(trim($atribute['username']));
		$user['address'] = h(trim($atribute['address']));
		$user['email'] = h(trim($atribute['email']));
		$user['phonenumber'] = h(trim($atribute['phonenumber']));
		$hashPswdObj = new DefaultPasswordHasher;
		$user['password'] = $hashPswdObj->hash($atribute['password']);
		if ($atribute['password'] == '') {
			$user['password'] = '';
		}
		$user['point_user'] = 0;
		$user['role_id'] = 1;
		$user['avatar'] = 'none.jbg';
		$user['created_date'] = date('Y-m-d h:i:s');
		$user['updated_date'] = date('Y-m-d h:i:s');
		return [
			'result' => 'success',
			'data' => $user,
		];
	}

	//Check Validate cho User không hash
	public function addUserNoHash($atribute)
	{
		$user = [];
		$user['username'] = h(trim($atribute['username']));
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

	//Kiểm tra mail
	public function checkmail($atribute)
	{
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

	//Tạo Orders
	public function createOrders($dataProds, $insertUser)
	{
		$order = [];
		$order['order_name'] = 'order_' . $insertUser[0]['username'];
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

		return $result;
	}

	//Tạo orders không login
	public function createOrdersNone($infoUser, $dataProds, $insertUser)
	{
		$order = [];
		$order['order_name'] = 'order_' . $infoUser['username'];
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
		return $result;
	}

	//Lấy Point của users
	public function getPointByUser($idUser)
	{
		$query = $this->Users->find()
			->select([
				'Users.id',
				'Users.point_user',
			])
			->where([
				'Users.id' => $idUser,
				'Users.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Cập nhật point
	public function updatePoint($pointAF, $idUsers)
	{
		$this->Users->query()
			->update()
			->set([
				'Users.point_user' => $pointAF,
			])
			->where([
				'Users.id' => $idUsers,
				'Users.del_flag' => 0
			])
			->execute();
	}

	//Lấy thông tin Users
	public function getInfoUser($idUser)
	{
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
				'Users.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Lấy thông tin User kể cả del_flag = 1
	public function getCheckInfoUser($idUser)
	{
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

	//Lấy tất cả Users
	public function getAllUser()
	{
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
				'table' => 'Roles',
				'alias' => 'Roles',
				'type' => 'inner',
				'conditions' => ['Users.role_id = Roles.id']
			]);
		return $query->toArray();
	}

	//Lấy Product Bằng ID
	public function getProductByID($product_id)
	{
		$query = $this->Products->find()
			->select([
				'Products.id',
				'Products.product_name',
				'Products.amount_product',
				'Products.point_product',
			])
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'products.id' => $product_id,
				'products.del_flag' => 0,
			]);
		return $query->toArray();
	}

	public function getNewsProduct()
	{
		$query = $this->Products->find()
			->where([
				'Products.del_flag' => 0,
			])
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->order('created_date DESC')
			->all();
		return $query;
	}

	//Lấy category
	public function getCategory()
	{
		$query = $this->Categories->find()
			->where([
				'Categories.del_flag' => 0,
			])
			->limit(5)
			->all();
		return $query;
	}

	//Lấy tất cả sản phẩm
	public function getAllProducts()
	{
		$query = $this->Products->find()
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->order('created_date DESC')
			->limit(2)
			->all();
		return $query;
	}

	//Kiểm tra Login
	public function checklogin($atribute)
	{
		$query = $this->Users->query()
			->Where([
				'Users.email' => $atribute['email'],
				'Users.del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Lấy tất cả Product bằng Danh mục
	public function getProductByCategory($id)
	{
		$query = $this->Products->query()
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->Where([
				'Products.category_id' => $id,
				'Products.del_flag' => 0,
			]);
		return $query;
	}

	//Lấy chi tiết sản phẩm bằng ID
	public function getDetailsProductByID($id)
	{
		$query = $this->Products->query()
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
				'table' => 'Categories',
				'alias' => 'Categories',
				'type' => 'inner',
				'conditions' => ['Products.category_id = Categories.id']
			])
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->Where([
				'Products.id' => $id,
				'Products.del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Lấy Ảnh của Products
	public function getImageByProduct($id)
	{
		$query = $this->Images->query()
			->Where([
				'Images.product_id' => $id,
				'Images.del_flag' => 0,
				'Images.image_type' => 'Banner',
			])
			->order('Images.updated_date DESC')
			->limit(5);
		return $query->toArray();
	}

	//Lấy sản phẩm liên quan
	public function similarProduct($idCategory, $idProduct)
	{
		$query = $this->Products->query()
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->Where([
				'Products.id NOT IN' => $idProduct,
				'Products.category_id' => $idCategory,
				'Products.del_flag' => 0,
			]);
		return $query;
	}

	//Lấy các đơn hàng bằng Users
	public function getOrdersByUser($idUsers)
	{
		$query = $this->Orders->find()
			->select([
				'Orders.id',
				'Orders.total_point',
				'Orders.total_amount',
				'Orders.created_date',
				'Orders.status'
			])
			->where([
				'Orders.user_id' => $idUsers
			])
			->order('Orders.id DESC');
		return $query;
	}
}
