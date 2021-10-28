<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Chronos\Date;
use Symfony\Component\VarDumper\Cloner\Data;

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

	public function getUserByID($id)
	{
		$query = $this->Users->find()
			->where([
				'Users.id' => $id,
			]);
		return $query->toArray();
	}

	public function getAllRoles()
	{
		$query = $this->Roles->find()
			->select([
				'Roles.id',
				'Roles.role_name',
			]);
		return $query->toArray();
	}

	public function addUser($atribute)
	{
		$user = [];
		$user['username'] = trim($atribute['username']);
		$user['address'] = trim($atribute['address']);
		$user['email'] = trim($atribute['email']);
		$user['phonenumber'] = trim($atribute['phonenumber']);
		$user['password'] = $atribute['password'];
		$user['role_id'] = $atribute['role_id'];
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
			'data' => $dataUser,
		];
	}

	public function getUser()
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
				'Users.del_flag',
				'Users.address',
				'Roles.role_name'
			])
			->join([
				'table' => 'Roles',
				'alias' => 'Roles',
				'type' => 'inner',
				'conditions' => ['Users.role_id = Roles.id']
			])
			->order(['Users.del_flag' => 'ASC', 'Users.id' => 'DESC']);
		return $query;
	}

	// Categories
	public function getAllCategory()
	{
		$query = $this->Categories->find()
			->select([
				'Categories.id',
				'Categories.category_name',
				'Categories.del_flag',
			])
			->where([
				'Categories.del_flag' => 0,
			])
			->order('Categories.id DESC');
		return $query;
	}

	//Check Category ID
	public function checkIDCategory($id)
	{
		$query = $this->Categories->find()
			->where([
				'Categories.id' => $id,
				'Categories.del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Check Order ID
	public function checkIDOrder($id)
	{
		$query = $this->Orders->find()
			->where([
				'Orders.id' => $id
			]);
		return $query->toArray();
	}

	//Check User ID
	public function checkIDUser($id)
	{
		$query = $this->Users->find()
			->where([
				'Users.id' => $id,
				'Users.del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Check Product ID
	public function checkIDProduct($id)
	{

		$query = $this->Products->find()
			->where([
				'Products.id' => $id,
				'Products.del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Check Order Details ID
	public function checkIDOrderDetails($id)
	{
		$query = $this->Orderdetails->find()
			->where([
				'Orderdetails.id' => $id,
			]);
		return $query->toArray();
	}

	public function getCategoryByID($id)
	{
		$query = $this->Categories->find()
			->where([
				'Categories.id' => $id,
				'Categories.del_flag' => 0
			]);
		return $query->toArray();
	}

	public function addCategory($atribute)
	{
		$category = [];
		$category['category_name'] = trim($atribute['category_name']);
		$category['created_date'] = date('Y-m-d h:i:s');
		$category['updated_date'] = date('Y-m-d h:i:s');
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

	public function checkProductByCategory($atribute)
	{
		$idCategory = $atribute['id'];
		$query = $this->Products->find()
			->where([
				'Products.category_id' => $idCategory,
				'Products.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Products
	public function getAllProduct()
	{
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
				'table' => 'Categories',
				'alias' => 'Categories',
				'type' => 'inner',
				'conditions' => ['Products.category_id = Categories.id']
			])
			->where([
				'Products.del_flag' => 0,
			])
			->order('Products.id DESC')
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}]);
		return $query;
	}

	public function addproduct($atribute)
	{
		$product = [];
		$product['product_name'] = trim($atribute['product_name']);
		$product['description'] = trim($atribute['description']);
		$product['amount_product'] = $atribute['amount_product'];
		$product['point_product'] = $atribute['point_product'];
		$product['category_id'] = $atribute['category_id'];
		$product['created_date'] = date('Y-m-d h:i:s');
		$product['updated_date'] = date('Y-m-d h:i:s');
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
		$targetPath = WWW_ROOT . 'img' . DS . $name;

		if ($name) {
			$image->moveTo($targetPath);
			$images->image = '../../img/' . $name;
		}

		$images->image_name = 'img' . $atribute['product_name'];
		$images->image_type = 'Banner';
		$images->user_id = 1;
		$images->product_id = $result['id'];
		$images->created_date = date('Y-m-d h:i:s');
		$images->updated_date = date('Y-m-d h:i:s');

		$this->Images->save($images);

		return [
			'result' => 'success',
			'data' => $this->Products->save($dataProduct),
		];
	}

	public function getProductByID($id)
	{
		$query = $this->Products->find()
			->where([
				'Products.id' => $id,
				'Products.del_flag' => 0
			])
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}]);
		return $query->toArray();
	}

	//Search
	public function getSearch($key)
	{
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
				'table' => 'Categories',
				'alias' => 'Categories',
				'type' => 'inner',
				'conditions' => ['Products.category_id = Categories.id']
			])
			->order('Products.created_date DESC')
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'Products.product_name like' => '%' . $key . '%',
				'Products.del_flag' => 0
			]);
		return $query;
	}

	//Search User
	public function getSearchUser($key)
	{
		$query = $this->Users->find()
			->where([
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Users.email like' => '%' . $key . '%']],
			])
			->order(['Users.del_flag' => 'ASC', 'Users.id' => 'DESC']);
		return $query;
	}

	//Orders
	public function getAllOrder()
	{
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
				'table' => 'Users',
				'alias' => 'Users',
				'type' => 'inner',
				'conditions' => ['Orders.user_id = Users.id']
			])
			->order('Orders.id DESC');
		return $query;
	}

	//Lấy chi tiết đơn hàng
	public function getOrderDetailsByID($id)
	{
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
				'table' => 'Orders',
				'alias' => 'Orders',
				'type' => 'inner',
				'conditions' => ['Orderdetails.order_id = Orders.id']
			])
			->join([
				'table' => 'Products',
				'alias' => 'Products',
				'type' => 'inner',
				'conditions' => ['Orderdetails.product_id = Products.id']
			])
			->where([
				'Orderdetails.order_id' => $id,
			]);
		return $query;
	}

	//Search Orders
	public function getSearchOrder($key)
	{

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
				'table' => 'Users',
				'alias' => 'Users',
				'type' => 'inner',
				'conditions' => ['Orders.user_id = Users.id']
			])
			->order('Orders.id DESC')
			->where([
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Orders.email like' => '%' . $key . '%']]
			]);
		return $query;
	}

	public function addorder($atribute)
	{
		$product = [];
		$product['product_name'] = trim($atribute['product_name']);
		$product['description'] = trim($atribute['description']);
		$product['amount_product'] = $atribute['amount_product'];
		$product['point_product'] = $atribute['point_product'];
		$product['category_id'] = $atribute['category_id'];
		$product['created_date'] = date('Y-m-d h:i:s');
		$product['updated_date'] = date('Y-m-d h:i:s');
		$dataProduct = $this->Products->newEntity($product);

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

	public function getOrderByID($id)
	{
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
				'table' => 'Users',
				'alias' => 'Users',
				'type' => 'inner',
				'conditions' => ['Orders.user_id = Users.id']
			])
			->where([
				'Orders.id' => $id,
			]);

		return $query->toArray();
	}

	public function register($atribute)
	{
		$user = [];
		$user['username'] = trim($atribute['fullname']);
		$user['address'] = trim($atribute['address']);
		$user['email'] = trim($atribute['email']);
		$user['phonenumber'] = $atribute['phonenumber'];
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
			'data' => $dataUser,
		];
	}

	public function getUsersByID($idUser)
	{
		$query = $this->Users->find()
			->where([
				'id' => $idUser,
			])
			->first();
		return $query;
	}

	public function getUsersByEmail($email)
	{
		$query = $this->Users->find()
			->where([
				'email' => $email,
			])
			->first();
		return $query;
	}

	public function getUsersByEmailArr($email)
	{
		$query = $this->Users->find()
			->where([
				'email' => $email,
			]);
		return $query->toArray();
	}

	public function checkDelFlagByEmail($email)
	{
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

	// Thống Kê
	/* Tổng số đơn hàng bán ra trong tháng hiện tại */
	public function totalOrderForYear(){
		$query = $this->Orders->find()
			->where([
				'Year(Orders.created_date)' => '20'.Date('y'),
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			]);
		return $query->toArray();
	}

	/*Tổng số đơn hàng bán ra trong các tháng */
	public function totalOrderAllMonth(){
		$query = $this->Orders->find()
			->select([
				'Month' => 'Month(Orders.created_date)',
				'Count' => 'count(Orders.id)'
			])
			->where([
				'Year(Orders.created_date)' => '20'.Date('y'),
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Month');
		return $query->toArray();
	}
	/*Tổng đơn hàng đã đặt trên website */
	public function totalOrder(){
		$query = $this->Orders->find()
			->where([
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			]);
		return $query->toArray();
	}

	/*Tổng đơn hàng trong các năm gần nhất */
	public function totalOrderAllYear(){
		$query = $this->Orders->find()
			->select([
				'Year' => 'Year(Orders.created_date)',
				'Count' => 'count(Orders.id)'
			])
			->where([
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Year');
		return $query->toArray();

	}
	/*Tổng số người dùng đã đặt hàng */
	public function totalUser(){
		$query = $this->Orders->find()
			->where([
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Orders.user_id');
		return $query->toArray();
	}
	/*Tổng người dùng đã đặt hàng của các tháng trong năm */
	public function totalUserForMonth(){
		$query = $this->Orders->find()
			->select([
				'Month' => 'Month(Orders.created_date)',
				'Count' => 'count(DISTINCT Orders.user_id)'
			])
			->where([
				'Year(Orders.created_date)' => '20'.Date('y'),
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Month');
		return $query->toArray();
	}

	/*Tổng sản phẩm đang bán trên website */
	public function totalProduct(){
		$query = $this->Products->find()
			->where([
				'Products.del_flag' => 0,
			]);
		return $query->toArray();
	}

	/*Tổng sản phẩm đã bán của các tháng */
	public function totalProductForMonth(){
		$query = $this->Orderdetails->find()
			->select([
				'Month' => 'Month(Orderdetails.created_date)',
				'Count' => 'count(DISTINCT Orderdetails.product_id)'
			])
			->join([
				'table' => 'Orders',
				'alias' => 'Orders',
				'type' => 'inner',
				'conditions' => ['Orderdetails.order_id = Orders.id']
			])
			->where([
				'Year(Orderdetails.created_date)' => '20'.Date('y'),
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Month');
		return $query->toArray();
	}

	//Doanh thu năm hiện tại
	public function revenue(){
		$query = $this->Orders->find()
			->select([
				'sum'=>'SUM(Orders.total_amount)'
			])
			->where([
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			]);
		return $query->toArray();
	}

	//Doanh thu các tháng trong năm hiện tại
	public function revenueForMonth(){
		$query = $this->Orders->find()
			->select([
				'Month' => 'Month(Orders.created_date)',
				'sum'=>'SUM(Orders.total_amount)'
			])
			->where([
				'Year(Orders.created_date)' => '20'.Date('y'),
				'OR' => [
					['Orders.status' => 0],
					['Orders.status' => 1],
				]
			])
			->group('Month');
		return $query->toArray();
	}

	//Set màu cho cột
	public function colorCurrentMonth(){
		$query = $this->Orders->find()
			->select([
				'Month' => 'Month(Orders.created_date)',
			])
			->where([
				'OR' => [
					['Month(Orders.created_date)' => Date('m')-1],
					['Month(Orders.created_date)' => Date('m')]
				],
			])
			->group('Month');
		return $query->toArray();
	}

}
