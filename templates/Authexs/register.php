
<?php
	echo $this->element('Register/header');
?>
<body>
    <div id="auth">

        <div class="container">
            
            <div class="row">
                <div class="col-md-7 col-sm-12 mx-auto">
                    <div class="card pt-4">
                        <div class="card-body">
                            <div class="text-center mb-5">
                                <img src="../../img/Admin/favicon.svg" height="48" class='mb-4'>
                                <h3>Sign Up</h3>
                                <p>Please fill the form to register.</p>
                            </div>
                            <form enctype="multipart/form-data" action="/register" method="POST">
                                <div class="row">
                                    <div class="col-12 col-md-6 order-md-1 order-last">
                                        <?= $this->Flash->render() ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="first-name-column">FullName</label>
                                            <input <?php  if(isset($_SESSION['error']['username'])){?> style="border-color: red; color: red;" <?php }?> type="text" id="first-name-column" class="form-control" name="fullname">
                                            <?php  if(isset($_SESSION['error']['username'])){?>
                                                        <i style="color: red;">
                                                            <?= implode($_SESSION['error']['username'])?>
                                                        </i>
                                                <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="username-column">Email</label>
                                            <input <?php  if(isset($_SESSION['error']['email'])){?> style="border-color: red; color: red;" <?php }?> type="text" id="username-column" class="form-control" name="email">
                                            <?php  if(isset($_SESSION['error']['email'])){?>
                                                        <i style="color: red;">
                                                            <?= implode($_SESSION['error']['email'])?>
                                                        </i>
                                                <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="country-floating">Phonenumber</label>
                                            <input <?php  if(isset($_SESSION['error']['phonenumber'])){?> style="border-color: red; color: red;" <?php }?> type="number" id="country-floating" class="form-control" name="phonenumber">
                                            <?php  if(isset($_SESSION['error']['phonenumber'])){?>
                                                        <i style="color: red;">
                                                            <?= implode($_SESSION['error']['phonenumber'])?>
                                                        </i>
                                                <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="last-name-column">Address</label>
                                            <input <?php  if(isset($_SESSION['error']['address'])){?> style="border-color: red; color: red;" <?php }?> type="text" id="last-name-column" class="form-control" name="address">
                                            <?php  if(isset($_SESSION['error']['address'])){?>
                                                        <i style="color: red;">
                                                            <?= implode($_SESSION['error']['address'])?>
                                                        </i>
                                                <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="company-column">Password</label>
                                            <input <?php  if(isset($_SESSION['error']['password'])){?> style="border-color: red; color: red;" <?php }?> type="password" id="company-column" class="form-control" name="password">
                                            <?php  if(isset($_SESSION['error']['password'])){?>
                                                        <i style="color: red;">
                                                            <?= implode($_SESSION['error']['password'])?>
                                                        </i>
                                                <?php }?>
                                        </div>
                                    </div>
                                </diV>
                                <a href="/login">Have an account? Login</a>
                                <div class="clearfix">
                                    <button class="btn btn-primary float-end">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
	echo $this->element('Register/footer');
?>