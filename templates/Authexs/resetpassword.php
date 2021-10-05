<?php
	echo $this->element('Register/header');
?>
<body>
    <div id="auth">

        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-12 mx-auto">
                    <div class="card py-4">
                        <div class="card-body">
                            <div class="text-center mb-5">
                                <img src="../../img/Admin/favicon.svg" height="48" class='mb-4'>
                                <h3>Reset Password</h3>
                                <p>Please enter your email to receive password reset link.</p>
                            </div>
                            <div class="row">
                                <?= $this->Flash->render() ?>
                            </div>
                            <?= $this->Form->create() ?>
                                <div class="form-group">
                                    <label for="first-name-column">Password</label>
                                    <input type="email" id="first-name-column" class="form-control" name="password">
                                </div>

                                <div class="clearfix">
                                    <button class="btn btn-primary float-end">Submit</button>
                                </div>
                                <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
	echo $this->element('Register/footer');
?>