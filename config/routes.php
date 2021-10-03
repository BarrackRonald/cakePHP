<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);


//Admin




$routes->scope('/', function (RouteBuilder $builder) {
    // $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([
    //     'httpOnly' => true,
    //  ]));
    //  $builder->applyMiddleware('csrf');
    //NormalUser
    $builder->connect('/',['controller'=>'NormalUsers','action'=>'index']);
    $builder->connect('/contact',['controller'=>'NormalUsers','action'=>'contact']);
    $builder->connect('/product',['controller'=>'NormalUsers','action'=>'product']);
    $builder->connect('/preview',['controller'=>'NormalUsers','action'=>'preview']);
    $builder->connect('/about',['controller'=>'NormalUsers','action'=>'about']);
    $builder->connect('/search',['controller'=>'NormalUsers','action'=>'search']);

    //Order
    $builder->connect('/addCart',['controller'=>'NormalUsers','action'=>'addCart']);
    $builder->connect('/dellCart',['controller'=>'NormalUsers','action'=>'dellCart']);
    $builder->connect('/dellAllCart',['controller'=>'NormalUsers','action'=>'dellAllCart']);
    $builder->connect('/carts',['controller'=>'NormalUsers','action'=>'informationCart']);
    $builder->connect('/successorder',['controller'=>'NormalUsers','action'=>'successOrder']);

    //Hóa đơn Order
    $builder->connect('/billorder',['controller'=>'NormalUsers','action'=>'billOrder']);

    //Xác nhận đăt hàng
    $builder->connect('/addorders',['controller'=>'NormalUsers','action'=>'addorders']);

    //Add User
    $builder->connect('/adduser',['controller'=>'NormalUsers','action'=>'adduser']);

    //Xác nhận đặt hàng không login

    $builder->connect('/addordersnonelogin',['controller'=>'NormalUsers','action'=>'addordersnonelogin']);

    //TestComponents
    $builder->connect('/testcomponents',['controller'=>'Testcomponents','action'=>'initialize']);

    //Test
    $builder->connect('/sendmail',['controller'=>'NormalUsers','action'=>'sendMail']);

    //Authexs
    $builder->connect('/auth',['controller'=>'Authexs','action'=>'index']);
    $builder->connect('/login',['controller'=>'Authexs','action'=>'login']);
    $builder->connect('/register',['controller'=>'Users','action'=>'register']);
    $builder->connect('/logout',['controller'=>'Authexs','action'=>'logout']);

    //redirect-controller
    $builder->connect('redirect-controller',['controller'=>'Redirects','action'=>'action1']);
    $builder->connect('redirect-controller2',['controller'=>'Redirects','action'=>'action2']);

    //Test
    $builder->connect('tests', ['controller' => 'Tests', 'action' => 'show']);

    //Views
    $builder->connect('template',['controller'=>'Products','action'=>'view']);

    //Extending views
    $builder->connect('extend',['controller'=>'Extends','action'=>'index']);

    //View Elements
    $builder->connect('element-example',['controller'=>'Elems','action'=>'index']);

    // Add
    $builder->connect('users/add', ['controller' => 'Users', 'action' => 'add']);

    //View Record
    $builder->connect('users/edit', ['controller' => 'Users', 'action' => 'edit']);

    //Delete
    $builder->connect('/users/delete', ['controller' => 'Users', 'action' => 'delete']);

    // $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    $builder->connect('pages/*', ['controller' => 'Pages', 'action' => 'display']);


    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */


    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */


    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/:controller', ['action' => 'index']);
     * $builder->connect('/:controller/:action/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks();
});


$routes->scope('/', function (RouteBuilder $builder) {
    $builder->connect('/admin',['controller'=>'Admin','action'=>'index']);

    //CRUD Users
    $builder->connect('/admin/add-user', ['controller' => 'Users', 'action' => 'addUser']);
    $builder->connect('/admin/edit-user/:id', ['controller' => 'Users', 'action' => 'editUser'], ["pass" => ["id"]]);
    $builder->connect('/admin/delete-user/:id', ['controller' => 'Users', 'action' => 'deleteUser'], ["pass" => ["id"]]);
    $builder->connect('/admin/list-user', ['controller' => 'Users', 'action' => 'listUsers']);
    $builder->connect('/admin/view-user/:id', ['controller' => 'Users', 'action' => 'viewUser'], ["pass" => ["id"]]);

    //CRUD Danh mục sản phẩm
    $builder->connect('/admin/add-category', ['controller' => 'Categories', 'action' => 'addCategory']);
    $builder->connect('/admin/edit-category/:id', ['controller' => 'Categories', 'action' => 'editCategory'], ["pass" => ["id"]]);
    $builder->connect('/admin/delete-category/:id', ['controller' => 'Categories', 'action' => 'deleteCategory'], ["pass" => ["id"]]);
    $builder->connect('/admin/list-categories', ['controller' => 'Categories', 'action' => 'listCategories']);
    $builder->connect('/admin/view-category/:id', ['controller' => 'Categories', 'action' => 'viewCategory'], ["pass" => ["id"]]);

    //CRUD Danh sách sản phẩm
    $builder->connect('/admin/add-product', ['controller' => 'Products', 'action' => 'addProduct']);
    $builder->connect('/admin/edit-product/:id', ['controller' => 'Products', 'action' => 'editProduct'], ["pass" => ["id"]]);
    $builder->connect('/admin/delete-product/:id', ['controller' => 'Products', 'action' => 'deleteProduct'], ["pass" => ["id"]]);
    $builder->connect('/admin/list-products', ['controller' => 'Products', 'action' => 'listProducts']);
    $builder->connect('/admin/view-product/:id', ['controller' => 'Products', 'action' => 'viewProduct'], ["pass" => ["id"]]);

    //CRUD Danh sách order
    $builder->connect('/admin/add-order', ['controller' => 'Orders', 'action' => 'addOrder']);
    $builder->connect('/admin/edit-order/:id', ['controller' => 'Orders', 'action' => 'editOrder'], ["pass" => ["id"]]);
    $builder->connect('/admin/delete-order/:id', ['controller' => 'Orders', 'action' => 'deleteOrder'], ["pass" => ["id"]]);
    $builder->connect('/admin/list-orders', ['controller' => 'Orders', 'action' => 'listOrders']);
    $builder->connect('/admin/view-order/:id', ['controller' => 'Orders', 'action' => 'viewOrder'], ["pass" => ["id"]]);

    $builder->fallbacks();
});





/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *
 *     // Parse specified extensions from URLs
 *     // $builder->setExtensions(['json', 'xml']);
 *
 *     // Connect API actions here.
 * });
 * ```
 */
