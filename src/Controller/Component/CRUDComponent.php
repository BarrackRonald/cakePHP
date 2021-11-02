<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;

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

	//Lấy Users bằng ID Users
	public function getUserByID($id)
	{
		$query = $this->Users->find()
			->where([
				'Users.id' => $id,
			]);
		return $query->toArray();
	}

	//Lấy Data trong bảng quyền
	public function getAllRoles()
	{
		$query = $this->Roles->find()
			->select([
				'Roles.id',
				'Roles.role_name',
			]);
		return $query->toArray();
	}

	//Add User
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

	//Lấy thông tin Users
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
			->order(['Users.id' => 'DESC']);
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

	//Check Order Details ID
	public function checkIDOrderDetails($id)
	{
		$query = $this->Orderdetails->find()
			->where([
				'Orderdetails.id' => $id,
			]);
		return $query->toArray();
	}

	//Lấy Danh mục bằng ID
	public function getCategoryByID($id)
	{
		$query = $this->Categories->find()
			->where([
				'Categories.id' => $id,
				'Categories.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Add Danh mục
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

	//Lấy Product theo Danh mục
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
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'Products.del_flag' => 0,
			])
			->order('Products.id DESC');
		return $query;
	}

	//Add Product
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
		if($result){
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
	}

	//Lấy Product bằng ID
	public function getProductByID($id)
	{
		$query = $this->Products->find()
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'Products.id' => $id,
				'Products.del_flag' => 0
			]);
		return $query->toArray();
	}

	//Search Products
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
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'Products.product_name like' => '%' . $key . '%',
				'Products.del_flag' => 0
			])
			->order('Products.created_date DESC');
		return $query;
	}

	//Search Product to Array
	public function getSearchtoArr($key)
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
			->contain(['Images' => function ($q) {
				return $q->order('Images.updated_date DESC');
			}])
			->where([
				'Products.product_name like' => '%' . $key . '%',
				'Products.del_flag' => 0
			])
			->order('Products.created_date DESC');
		return $query->toArray();
	}

	//Search User
	public function getSearchUser($key)
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
			->where([
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Users.email like' => '%' . $key . '%']],
			])
			->order(['Users.del_flag' => 'ASC', 'Users.id' => 'DESC']);
		return $query;
	}

	public function getSearchUsertoArr($key)
	{
		$query = $this->Users->find()
			->where([
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Users.email like' => '%' . $key . '%']],
			])
			->order(['Users.del_flag' => 'ASC', 'Users.id' => 'DESC']);
		return $query->toArray();
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
				'Orders.total_point',
				'Orders.total_quantity',
				'Orders.total_amount',
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
			->where([
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Orders.email like' => '%' . $key . '%']]
			])
			->order('Orders.id DESC');
		return $query;
	}

	//Search Orders to Array
	public function getSearchOrderArr($key)
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
				'OR' => [['Users.username like' => '%' . $key . '%'], ['Orders.email like' => '%' . $key . '%']]
			])
			->order('Orders.id DESC');
		return $query->toArray();
	}

	//Add Orders
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

	//Lấy Order bằng ID
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

	//Đăng ký
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

	//Lấy User bằng ID
	public function getUsersByID($idUser)
	{
		$query = $this->Users->find()
			->where([
				'id' => $idUser,
			])
			->first();
		return $query;
	}

	//Lấy User bằng email
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
				'del_flag' => 0,
			]);
		return $query->toArray();
	}

	//Check Del Flag bằng Email
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

	//Lấy doanh thu tháng trước và tháng hiện tại
	public function revenueCurrentMonth(){
		$query = $this->Orders->find()
		->select([
			'Month' => 'Month(Orders.created_date)',
			'sum'=>'SUM(Orders.total_amount)'
		])
		->where(function (QueryExpression $exp, Query $query) {
			$Month = $query->newExpr()->or(['Month(Orders.created_date)' => Date('m')-1])->add(['Month(Orders.created_date)' => Date('m')]);
			$status = $query->newExpr()->or(['Orders.status' => 0])->add(['Orders.status' => 1]);
			return $exp->or([
				$query->newExpr()->and([$Month, $status])
			]);
		})
		->group('Month');
		return $query->toArray();
	}

	//Lọc
	public function filterUser($filters){
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
			->where([
				'Users.del_flag' => $filters,
			])
			->order(['Users.del_flag' => 'ASC', 'Users.id' => 'DESC']);
		return $query;
	}

}
