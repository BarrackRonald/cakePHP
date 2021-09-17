<?php
declare(strict_types=1);
namespace App\Controller;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
class RedirectsController extends AppController {
   public function action1() {
   }
   public function action2(){
      echo "redirecting from action2";
      $this->setAction('action1');
   }
}