<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
                        <li class="dropdown nav-icon">
                            <a href="#" data-bs-toggle="dropdown"
                                class="nav-link  dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-lg-inline-block">
                                    <i data-feather="bell"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-large">
                                <h6 class='py-2 px-4'>Notifications</h6>
                                <ul class="list-group rounded-none">
                                    <li class="list-group-item border-0 align-items-start">
                                        <div class="avatar bg-success me-3">
                                            <span class="avatar-content"><i data-feather="shopping-cart"></i></span>
                                        </div>
                                        <div>
                                            <h6 class='text-bold'>New Order</h6>
                                            <p class='text-xs'>
                                                An order made by Ahmad Saugi for product Samsung Galaxy S69
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="dropdown nav-icon me-2">
                            <a href="#" data-bs-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-lg-inline-block">
                                    <i data-feather="mail"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="user"></i> Account</a>
                                <a class="dropdown-item active" href="#"><i data-feather="mail"></i> Messages</a>
                                <a class="dropdown-item" href="#"><i data-feather="settings"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#" data-bs-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar me-1">
                                    <img src="../../img/Admin/avatar/avatar-s-1.png" alt="" srcset="">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">Hi, Saugi</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="user"></i> Account</a>
                                <a class="dropdown-item active" href="#"><i data-feather="mail"></i> Messages</a>
                                <a class="dropdown-item" href="#"><i data-feather="settings"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Add User</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class='breadcrumb-header'>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Datatable</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create() ?>
                            <div class="form-group">
                            <label for="email">Username:</label>
                                <input <?php  if(isset($_SESSION['error']['username'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="username" >
                                <?php  if(isset($_SESSION['error']['username'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['username'])?>
                                        </i>
                                <?php }?>

                            </div>
                            <div class="form-group">
                            <label for="email">Password:</label>
                                <input <?php  if(isset($_SESSION['error']['password'])){?> style="border-color: red; color: red;" <?php }?> type="password" class="form-control" value="" name="password" >
                                <?php  if(isset($_SESSION['error']['password'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['password'])?>
                                        </i>
                                <?php }?>

                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input <?php  if(isset($_SESSION['error']['email'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="email" >
                                <?php  if(isset($_SESSION['error']['email'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['email'])?>
                                        </i>
                                <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Phonenumber:</label>
                                    <input <?php  if(isset($_SESSION['error']['phonenumber'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="phonenumber" >
                                    <?php  if(isset($_SESSION['error']['phonenumber'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['phonenumber'])?>
                                            </i>
                                    <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Address:</label>
                                    <input <?php  if(isset($_SESSION['error']['address'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="address" >
                                    <?php  if(isset($_SESSION['error']['address'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['address'])?>
                                            </i>
                                    <?php }?>
                            </div>

                            <div class="form-group">
                                <label for="email">Point:</label>
                                    <input <?php  if(isset($_SESSION['error']['point'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="point" >
                                    <?php  if(isset($_SESSION['error']['point'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['point'])?>
                                            </i>
                                    <?php }?>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Role:</label>
                                <select name="role_id" id="" class="form-control" >
                                    <?php foreach ($dataRole as $role) { ?>
                                        <option value="<?= $role->id?>"><?= $role->role_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if($_SESSION['flag'] == 2){ ?>
                                <button type="submit" class="btn btn-primary btn-default">Submit</button>
                            <?php }else{?>
                                <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                            <?php }?>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>