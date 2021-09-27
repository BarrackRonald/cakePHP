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
    }

    public function getSlideImage($key = null) {
        $query = $this->Images->find()
            ->where(['Images.image_type' => 'Slider'])
            ->order('created_date DESC')
            ->limit(5)
            ->all();
        // dd($query);
        return $query;
    }

    public function getSearch($keyword){

        $query = $this->Products->find()
            ->where([
                'Products.product_name like' => '%' . $keyword . '%',
                // 'Products.description like' => '%' . $keyword . '%'
            ]);
            // dd($query);
        return $query;

    }

    public function getProductByID($product_id){
        $query = $this->Products->find()
            ->select([
                'Products.id',
                'Products.product_name',
                'Products.amount_product',
                'Products.point_product',
                'Images.file'
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
        dd($ac->getErrors());
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
        // dd($usersTable->save($user));
    }

    public function getAllProduct(){
        

    }
}
