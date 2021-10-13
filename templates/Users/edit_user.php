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
                            <h3>Chỉnh sửa thông tin User</h3>
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
                        <?= $this->Form->create($dataUser[0]) ?>
                            <div class="form-group">
                            <label for="email">Username:</label>
                                <input type="text" class="form-control" value="<?= trim($dataUser[0]->username) ?>" name="username" >
                                <?php  if(isset($_SESSION['error']['username'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['username'])?>
                                        </i>
                                <?php }?>

                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" class="form-control" value="<?= $dataUser[0]->password ?>" name="password" >
                                <?php  if(isset($_SESSION['error']['password'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['password'])?>
                                        </i>
                                <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Phonenumber:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->phonenumber ?>" name="phonenumber" onkeypress='validate(event)'  maxlength = "10" >
                                    <?php  if(isset($_SESSION['error']['phonenumber'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['phonenumber'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Address:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->address ?>" name="address" >
                                    <?php  if(isset($_SESSION['error']['address'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['address'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <?php if(!isset($_SESSION['hasReferer'])){?>
                            <input type="hidden" class="form-control" value="<?php if(isset($_SESSION['referer'])){ echo $_SESSION['referer']; }?>" name="referer" >
                            <?php }?>

                            <div class="form-group">
                                <label for="pwd">Role:</label>
                                <select name="role_id" id="" class="form-control" >
                                    <?php foreach ($dataRole as $role) { ?>
                                        <option value="<?= $role->id?>" <?php if($role->id == $dataUser[0]->role_id){?> selected <?php } ?> ><?= $role->role_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="button_back">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <a href="<?php if(isset($_SESSION['referer'])){ echo $_SESSION['referer']; }?>">
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
        <?php } else {?>
            <h3>Người dùng không đủ quyền truy cập</h3>
        <?php }?>
<?php
echo $this->element('Admin/footer');
?>
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>