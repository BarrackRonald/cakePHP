<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\FactoryLocator;

class CommonComponent extends Component
{
    protected $controller;

    public function initialize(array $config): void
    {
        $this->controller = $this->getController();
    }

    public function loadModel($models): void
    {
        if (is_array($models)) {
            foreach ($models as $model) {
                $this->{$model} = FactoryLocator::get('Table')->get($model);
            }
        } else {
            $this->{$models} =  FactoryLocator::get('Table')->get($models);
        }
    }

    public function loadNewModel($alias, $config)
    {
        $this->{$alias} = FactoryLocator::get('Table')->get($alias, $config);
    }
}
