<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Data');
		$this->loadComponent('CRUD');
		$this->loadModel("Products");
		$this->loadModel("Images");
	}

	public function beforeFilter(EventInterface $event)
	{
		$session = $this->request->getSession();
		$flag = $session->read('flag');
		if (!$session->check('flag') || $flag == 1) {
			$this->Flash->error(__('Bạn không có quyền truy cập vào trang Admin.'));
			return $this->redirect('/');
		}
	}

	public function beforeRender(EventInterface $event)
	{
		$session = $this->request->getSession();
		if ($session->check('idUser')) {
			$idUsers = $session->read('idUser');
			$dataNameForUser = $this->{'Data'}->getInfoUser($idUsers);
			$this->set(compact('dataNameForUser'));
		}
	}

	//List Products
	public function listProducts()
	{
		$products = $this->{'CRUD'}->getAllProduct();
		$session = $this->request->getSession();

		//Search
		$key = $this->request->getQuery('key');
		if ($key) {
			//Lưu key
			$session->write('keySearch', trim($key));
			$query = $this->{'CRUD'}->getSearch(trim($key));
			$querytoArr = $this->{'CRUD'}->getSearchtoArr(trim($key));
			if(count($querytoArr) == 0){
				$this->Flash->error(__('Không tìm thấy kết quả tìm kiếm!!!'));
			}
		} else {
			if ($session->check('keySearch')) {
				$session->delete('keySearch');
			}

			$query = $products;
		}

		//Pagination
		try {
			$this->set(compact('query', $this->paginate($query, ['limit' => '3'])));
		} catch (NotFoundException $e) {
			$atribute = $this->request->getAttribute('paging');
			$requestedPage = $atribute['Products']['requestedPage'];
			$pageCount = $atribute['Products']['pageCount'];
			if ($requestedPage > $pageCount) {
				return $this->redirect("/admin/list-products?page=" . $pageCount . "");
			}
		}
	}

	//Add Product
	public function addProduct()
	{
		$dataCategory =  $this->{'CRUD'}->getAllCategory();
		$product = $this->Products->newEmptyEntity();
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();

			//Check F12
			$idCategory = $atribute['category_id'];
			$checkIDCategory = $this->{'CRUD'}->getCategoryByID($idCategory);
			if (count($checkIDCategory) < 1) {
				$this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận chỉnh sửa Sản phẩm!!!'));
				return $this->redirect(['action' => 'listProducts']);
			}else{
				$dataProduct = $this->{'CRUD'}->addproduct($atribute);
				if ($dataProduct['result'] == "invalid") {
					$error = $dataProduct['data'];
					$this->set('error', $error);
					$data = $atribute;
					$this->set('dataProduct', $data);
				} else {
					$this->Flash->success(__('Sản phẩm đã được thêm thành công.'));
					return $this->redirect(['action' => 'listProducts']);
				}
			}
		}
		$this->set(compact('dataCategory'));
	}

	//Edit Product
	public function editProduct($id = null)
	{
		//Check URL_ID
		if (!is_numeric($id)) {
			$this->Flash->error(__('Sản phẩm không tồn tại.'));
			return $this->redirect(['action' => 'listProducts']);
		} else {
			$checkProductID = $this->{'CRUD'}->getProductByID($id);
			if (count($checkProductID) < 1) {
				$this->Flash->error(__('Sản phẩm không tồn tại.'));
				return $this->redirect(['action' => 'listProducts']);
			}
		}

		//Check ID User
		$dataCategory =  $this->{'CRUD'}->getAllCategory();
		$dataProduct = $this->{'CRUD'}->getProductByID($id);
		if ($this->request->is('post')) {
			$atribute = $this->request->getData();
			//Check thay đổi
			if (
				trim($atribute['product_name']) == trim($dataProduct[0]['product_name']) &&
				trim($atribute['description']) == trim($dataProduct[0]['description']) &&
				trim($atribute['amount_product']) == $dataProduct[0]['amount_product'] &&
				trim($atribute['point_product']) == $dataProduct[0]['point_product'] &&
				trim($atribute['category_id']) == $dataProduct[0]['category_id'] &&
				$atribute['uploadfile']->getClientFilename() == ""
			) {
				$this->Flash->error(__('Sản phẩm không có sự thay đổi.'));
				$data = $atribute;
			} else {
				//Check F12
				$idCategory = $atribute['category_id'];
				$checkIDCategory = $this->{'CRUD'}->getCategoryByID($idCategory);
				if (count($checkIDCategory) < 1) {
					$this->Flash->error(__('Dữ liệu đã bị thay đổi. Không thể xác nhận chỉnh sửa Sản phẩm!!!'));
					$data = $atribute;
				}

				//Chỉnh sửa nếu có sự thay đổi ảnh
					$product = $this->Products->patchEntity($dataProduct[0], $atribute);

				if ($product->hasErrors()) {
					$error = $product->getErrors();
					$this->set('error', $error);
					$data = $atribute;
				} else {
					$result = $this->Products->save($product);
					if ($atribute['uploadfile']->getClientFilename() != "") {
						//Add Image vào Table Image
						$images = $this->Images->newEmptyEntity();
						$image = $atribute['uploadfile'];
						$name = $image->getClientFilename();
						$targetPath = WWW_ROOT . 'img' . DS . time() . $name;
						if ($name) {
							$image->moveTo($targetPath);
							$images->image = '../../img/' . time() . $name;
						}
						$images->image_name = 'img' . $atribute['product_name'];
						$images->image_type = 'Banner';
						$images->user_id = 1;
						$images->product_id = $result['id'];
						$images->updated_date = date('Y-m-d h:i:s');
						$this->Images->save($images);
					}
					if ($result) {
						$this->Flash->success(__('Sản phẩm đã được cập nhật thành công.'));
						return $this->redirect($atribute['referer']);
					}
				}
			}
		} else {
			$data = $dataProduct[0];
			$data["referer"] = $this->referer();
			if ($data["referer"] == "/") {
				return $this->redirect(['action' => 'listProducts']);
			}
		}
		$this->set('dataProduct', $data);
		$this->set(compact('dataCategory'));
	}

	//Delete soft Product
	public function deleteProduct($id = null)
	{
		$urlPageList = $_SERVER['HTTP_REFERER'];
		$this->request->allowMethod(['post', 'delete']);
		$dataProduct = $this->{'CRUD'}->getProductByID($id);
		$atribute = $this->request->getData();
		$atribute['del_flag'] = 1;
		$product = $this->Products->patchEntity($dataProduct[0], $atribute);
		if ($this->Products->save($product)) {
			$this->Flash->success(__('Sản phẩm đã được xóa thành công.'));
			return $this->redirect("$urlPageList");
		} else {
			$this->Flash->error(__('Sản phẩm chưa được xóa. Vui lòng thử lại.'));
			return $this->redirect("$urlPageList");
		}
	}
}