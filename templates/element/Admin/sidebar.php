<div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <img src="../../img/Admin/logo.svg" alt="" srcset="">
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">


                        <li class='sidebar-title'>Main Menu</li>



                        <li class="sidebar-item active ">
                            <a href="/admin" class='sidebar-link'>
                                <i data-feather="home" width="20"></i>
                                <span>Dashboard</span>
                            </a>

                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="triangle" width="20"></i>
                                <span>Components</span>
                            </a>

                            <ul class="submenu ">

                                <li>
                                    <a href="component-alert.html">Alert</a>
                                </li>
                            </ul>

                        </li>

                        <li class='sidebar-title'>Forms &amp; Tables</li>


                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="file-text" width="20"></i> 
                                <span>Quản lý Users</span>
                            </a>
                            
                            <ul class="submenu ">
                                
                                <li>
                                    <a href="<?= $this->Url->build('admin/list-user', ['fullBase' => true]) ?>">List User</a>
                                </li>
                                
                                <li>
                                    <a href="<?= $this->Url->build('admin/add-user', ['fullBase' => true]) ?>">Add User</a>
                                </li>

                            </ul>
                            
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="file-text" width="20"></i> 
                                <span>Quản lý danh mục</span>
                            </a>
                            
                            <ul class="submenu ">
                                
                                <li>
                                    <a href="<?= $this->Url->build('admin/list-categories', ['fullBase' => true]) ?>">List danh mục</a>
                                </li>
                                
                                <li>
                                    <a href="<?= $this->Url->build('admin/add-category', ['fullBase' => true]) ?>">Add danh mục</a>
                                </li>

                            </ul>
                            
                        </li>

                        <li class='sidebar-title'>Pages</li>



                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i data-feather="user" width="20"></i>
                                <span>Authentication</span>
                            </a>

                            <ul class="submenu ">

                                <li>
                                    <a href="auth-login.html">Login</a>
                                </li>

                                <li>
                                    <a href="auth-register.html">Register</a>
                                </li>

                                <li>
                                    <a href="auth-forgot-password.html">Forgot Password</a>
                                </li>

                            </ul>

                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>