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
         <li><a
               class="text-light"
               href="#login"
            >Login</a></li>
         <li><a
               class="text-light"
               href="#register"
            >Register</a></li>
      </ul>
   </nav>
</header>

<!-- Main -->
<div id="main">
   <article id="login">
      <h2 class="major">LOGIN</h2>
      <?php if ($this->session->flashdata('message')): ?>
      <div class="alert alert-warning">
         <?=$this->session->flashdata('message');?>
      </div>
      <?php endif?>

      <form
         method="post"
         action="<?=base_url('auth/index/login');?>"
      >
         <div class="form-group">
            <input
               type="text"
               id="login_email"
               name="login_email"
               placeholder="Email"
               autofocus=""
            >
            <?=form_error('login_email')?>
         </div>
         <div class="form-group">
            <input
               type="password"
               id="login_password"
               name="login_password"
               placeholder="Password"
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

      <hr class="mb-4">

      <div class="text-center">
         <a
            class="text-light text-decoration-none"
            href="<?=base_url('#register')?>"
         >Create an Account!</a>
      </div>
   </article>

   <article id="register">
      <h2 class="major">REGISTER</h2>
      <?php if ($this->session->flashdata('message')): ?>
      <div class="alert alert-warning">
         <?=$this->session->flashdata('message');?>
      </div>
      <?php endif?>

      <form
         method="post"
         action="<?=base_url('auth/index/registration');?>"
      >
         <div class="form-group">
            <input
               type="text"
               name="name"
               id="name"
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
                  placeholder="Password"
               >
               <?=form_error('password1')?>
            </div>
            <div class="col-sm-6">
               <input
                  type="password"
                  name="password2"
                  id="password2"
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
      <hr class="mb-4">
      <div class="text-center">
         <a
            class="text-light text-decoration-none"
            href="<?=base_url('#login')?>"
         >Already have an account? Login!</a>
      </div>
   </article>
</div>