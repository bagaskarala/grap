<?= $this->session->flashdata('message'); ?>
<!-- Login -->
<div id="main">
   <!-- Intro -->
   <article id="login">
      <h2 class="major">LOGIN</h2>
      <?php $this->session->flashdata('message'); ?>
      <h1>mantap</h1>
      <form
         class="user"
         method="post"
         action="<?= base_url('auth'); ?>"
         name="Login_Form"
         class="form-signin"
      >
         <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Username"
            required=""
            autofocus=""
         ><br>
         <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Password"
            required=""
         ><br>
         <button
            class="btn btn-lg btn-primary btn-block"
            type="submit"
            name="Submit"
            value="Login"
            type="Submit"
         >Login</button>
      </form>
   </article>
   <!-- Register -->
   <?php include "registration.php"; ?>
</div>