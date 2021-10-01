<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

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

    public function editUsers($id){

    }
}
