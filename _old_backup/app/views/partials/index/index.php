        <?php 
        $page_id = null;
        $comp_model = new SharedController;
        ?>
        <div class="container d-flex justify-content-center align-items-center w-100">
            <div class="col-md-6 col-lg-5">
                <div class="glass-panel  ">
                    
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="<?php print_link(SITE_LOGO); ?>" alt="Logo" style="max-height: 80px;" />
                        </div>
                        <h2 class="font-weight-bold">Welcome To <?php echo SITE_NAME ?></h2>
                        <p class="text-muted">Sign in to continue</p>
                    </div>

                    <?php $this :: display_page_errors(); ?>
                    
                    <form name="loginForm" action="<?php print_link('index/login/?csrf_token=' . Csrf::$token); ?>" class="needs-validation form page-form" method="post">
                        <div class="input-group form-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text glass-input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input placeholder="Username Or Email" name="username" required="required" class="form-control glass-input" type="text" />
                        </div>
                        
                        <div class="input-group form-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text glass-input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input placeholder="Password" required="required" v-model="user.password" name="password" class="form-control glass-input" type="password" />
                        </div>
                        
                        <div class="row align-items-center mb-4">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="rememberme" name="rememberme" value="true">
                                    <label class="custom-control-label text-dark" for="rememberme">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="<?php print_link('passwordmanager') ?>" class="text-info">Reset Password?</a>
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-3">
                            <button class="btn btn-modern btn-block btn-lg" type="submit"> 
                                <i class="load-indicator">
                                    <clip-loader :loading="loading" color="#fff" size="20px"></clip-loader> 
                                </i>
                                Login
                            </button>
                        </div>
                        
                        <hr class="my-4" style="border-top: 1px solid rgba(22, 163, 74, 0.2);" />
                        
                        <div class="text-center text-dark">
                            Don't Have an Account? <a href="<?php print_link("index/register") ?>" class="text-info font-weight-bold ml-1">Register Here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
