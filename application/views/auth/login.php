<div class="container">
   <!-- Outer Row -->
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
                                 id="email"
                                 name="email"
                                 placeholder="Email"
                                 autofocus=""
                                 class="form-control form-control-user"
                              >
                              <?=form_error('email', '<small class="text-danger">', '</small>')?>
                           </div>
                           <div class="form-group">
                              <input
                                 type="password"
                                 class="form-control"
                                 id="password"
                                 name="password"
                                 placeholder="Password"
                                 class="form-control form-control-user"
                              >
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
                              href="<?=base_url('auth/registration')?>"
                           >Create an Account!</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>