<div id="wrapper">
   <div class="container">
      <!-- Header -->
      <header id="header">

         <div class="content">
            <div class="inner">
               <h1> </h1>
               <br>
               <br>
               <br>
               <br>
               <br>
               <br>
               <br>
            </div>
         </div>
         <nav>
            <ul>
               <li><a href="#login">Login</a></li>
               <li><a href="#register">Register</a></li>
            </ul>
         </nav>
      </header>

      <!-- Outer Row -->
      <div id="main">
         <article id="login">
            <div class="row justify-content-center">
               <div class="col-lg-7">
                  <div class="card o-hidden border-0 shadow-lg my-5">
                     <div class="card-body p-0">
                        <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-warning">
                           <?=$this->session->flashdata('message');?>
                        </div>
                        <?php endif?>
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                           <div class="col">
                              <div class="p-5">
                                 <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
                                 </div>

                                 <form
                                    method="post"
                                    action="<?=base_url('auth');?>"
                                 >
                                    <div class="form-group">
                                       <input
                                          type="text"
                                          class="form-control"
                                          class="form-signin"
                                          id="login_email"
                                          name="login_email"
                                          placeholder="Email"
                                          autofocus=""
                                          class="form-control form-control-user"
                                       >
                                       <?=form_error('login_email')?>
                                    </div>
                                    <div class="form-group">
                                       <input
                                          type="password"
                                          class="form-control"
                                          id="login_password"
                                          name="login_password"
                                          placeholder="Password"
                                          class="form-control form-control-user"
                                       >
                                       <?=form_error('login_password')?>
                                    </div>
                                    <button
                                       type="submit"
                                       class="btn btn-primary btn-user btn-block"
                                    >
                                       Login
                                    </button>
                                 </form>

                                 <hr>


                                 <div class="text-center">
                                    <a
                                       class="small"
                                       href="#"
                                    >Forgot Password?</a>
                                 </div>
                                 <div class="text-center">
                                    <a
                                       class="small"
                                       href="<?=base_url('#register')?>"
                                    >Create an Account!</a>
                                 </div>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </article>

         <article id="register">
            <div class="container">

               <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
                  <div class="card-body p-0">
                     <!-- Nested Row within Card Body -->
                     <div class="row">
                        <div class="col-lg">
                           <div class="p-5">
                              <div class="text-center">
                                 <div class="alert alert-danger"><?=validation_errors();?></div>
                                 <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                              </div>
                              <form
                                 method="post"
                                 action="<?=base_url('auth/registration');?>"
                              >
                                 <div class="form-group">
                                    <input
                                       type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-user"
                                       placeholder="Enter your name"
                                       value="<?=set_value('name')?>"
                                    >
                                    <?=form_error('name')?>
                                 </div>
                                 <div class="form-group">
                                    <input
                                       type="text"
                                       name="email"
                                       id="email"
                                       class="form-control form-control-user"
                                       placeholder="Enter your email"
                                       value="<?=set_value('email')?>"
                                    >
                                    <?=form_error('email')?>
                                 </div>
                                 <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                       <input
                                          type="password"
                                          name="password1"
                                          id="password1"
                                          class="form-control form-control-user"
                                          placeholder="Password"
                                       >
                                       <?=form_error('password1')?>
                                    </div>
                                    <div class="col-sm-6">
                                       <input
                                          type="password"
                                          name="password2"
                                          id="password2"
                                          class="form-control form-control-user"
                                          placeholder="Re-Type Password"
                                       >
                                       <?=form_error('password2')?>
                                    </div>
                                 </div>
                                 <button
                                    type="submit"
                                    class="btn btn-primary btn-user btn-block"
                                 >
                                    Register Account
                                 </button>
                              </form>
                              <hr>
                              <div class="text-center">
                                 <a
                                    class="small"
                                    href="#"
                                 >Forgot Password?</a>
                              </div>
                              <div class="text-center">
                                 <a
                                    class="small"
                                    href="<?=base_url('#login')?>"
                                 >Already have an account? Login!</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

            </div>
         </article>
      </div>
   </div>
</div>