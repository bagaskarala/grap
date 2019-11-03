<!-- <!DOCTYPE HTML>
<html>

<head>
   <title>SUPER GRAPPLER</title>
   <meta charset="utf-8" />
   <meta
      name="viewport"
      content="width=device-width, initial-scale=1, user-scalable=no"
   />
   <link
      rel="stylesheet"
      href="assets/css/main.css"
      <?php echo base_url() ?>
   />
   <noscript>
      <link
         rel="stylesheet"
         href="assets/css/noscript.css"
      /></noscript>

</head> -->

<body class="is-preload">
   <!-- Wrapper -->
   <div id="wrapper">

      <!-- Header -->
      <!-- <header id="header">

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
      </header> -->

      <?=$this->session->flashdata('message');?>
      <!-- Login -->
      <div id="main">

         <!-- Intro -->
         <article id="login">
            <h2 class="major">LOGIN</h2>

            <?php $this->session->flashdata('message');?>

            <form
               class="user"
               method="post"
               action="<?=base_url('auth');?>"
               name="Login_Form"
               class="form-signin"
            >
               <!-- <h3 class="form-signin-heading" align="center">Welcome to Grappling! Silahkan Login!</h3> -->
               <!-- <hr class="colorgraph"> -->

               <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                  placeholder="Username"
                  required=""
                  autofocus=""
                  value=" <?php set_value('name')?>"
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
         <article id="register">
            <h2 class="major">Register</h2>
            <form
               method="post"
               action="<?=base_url('auth/registration');?>"
            >
               <div class="fields">
                  <div class="field half">
                     <label for="name">Username</label>
                     <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Full Name"
                     />

                  </div>
                  <div class="field half">
                     <label for="email">Password</label>
                     <input
                        type="password"
                        name="password1"
                        id="password1"
                        placeholder="Password"
                     />

                  </div>
               </div>
               <div class="field half">
                  <label for="email">Re-Type Password</label>
                  <input
                     type="password"
                     name="password2"
                     id="password2"
                     placeholder="Re-Type Password"
                  /> <br>
               </div>
               <ul class="actions">
                  <li><input
                        type="submit"
                        value="Register Account"
                        class="primary"
                     /></li>
               </ul>


            </form>

         </article>
      </div>
      <!-- Footer -->
      <footer id="footer">

      </footer>

   </div>

   <!-- BG -->
   <div id="bg"></div>

   <!-- Scripts -->
   <script src="assets/js/jquery.min.js"></script>
   <script src="assets/js/browser.min.js"></script>
   <script src="assets/js/breakpoints.min.js"></script>
   <script src="assets/js/util.js"></script>
   <script src="assets/js/main.js"></script>

</body>

</html>