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
                           href="<?=base_url('auth')?>"
                        >Already have an account? Login!</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>
</article