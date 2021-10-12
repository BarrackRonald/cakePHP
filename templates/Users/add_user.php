<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if($_SESSION['flag'] == 2){ ?>
            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Add User</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class='breadcrumb-header'>

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
                                    <input  <?php  if(isset($_SESSION['error']['phonenumber'])){?> style="border-color: red; color: red;" <?php }?> type="text" class="form-control" value="" name="phonenumber" >
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
                            <div class="button_back">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <a href="/admin/list-user">
                                        <button type="button" class="btn btn-primary btn-default">Back</button>
                                    </a>
                                <?php }else{?>
                                    <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                                <?php }?>

                            </div>

                            <div class="button_submit">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <button type="submit" class="btn btn-primary btn-default">Submit</button>
                                <?php }else{?>
                                    <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                                <?php }?>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else{?>
            <h3>Người dùng không đủ quyền để truy cập</h3>
        <?php }?>
<?php
echo $this->element('Admin/footer');
?>