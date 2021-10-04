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
                                <h3>Forgot Password</h3>
                                <p>Please enter your email to receive password reset link.</p>
                            </div>
                            <form action="index.html">
                                <div class="form-group">
                                    <label for="first-name-column">Email</label>
                                    <input type="email" id="first-name-column" class="form-control" name="fname-column">
                                </div>

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