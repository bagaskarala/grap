<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset="utf-8">
   <meta
      http-equiv="X-UA-Compatible"
      content="IE=edge"
   >

   <link
      rel="stylesheet"
      href="assets/css/main.css"
   />
   <noscript>
      <link
         rel="stylesheet"
         href="assets/css/noscript.css"
      /></noscript>

   <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no"
   >
   <meta
      name="description"
      content=""
   >
   <meta
      name="author"
      content=""
   >

   <title><?=$title?></title>

   <!-- Custom fonts for this template-->
   <link
      href="<?=base_url('assets/vendor/fontawesome-free/css/all.min.css')?>"
      rel="stylesheet"
      type="text/css"
   >
   <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
   >

   <!-- Custom styles for this template-->
   <!-- <link
      href="<?=base_url('assets/css/sb-admin-2.min.css')?>"
      rel="stylesheet"
   > -->

</head>

<body class="is-preload">
   <div id="app">
      <!-- Menampilkan halaman tertentu -->
      <?php $this->load->view($page)?>
   </div>

   <!-- Vue dist files -->
   <script src="<?=base_url('assets/dist/app.js')?>"></script>

   <!-- Bootstrap core JavaScript-->
   <script src="<?=base_url('assets/vendor/jquery/jquery.min.js')?>"></script>
   <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

   <!-- Core plugin JavaScript-->
   <script src="<?=base_url('assets/vendor/jquery-easing/jquery.easing.min.js')?>"></script>

   <!-- Custom scripts for all pages-->
   <script src="<?=base_url('assets/js/sb-admin-2.min.js')?>"></script>

   <!-- BG -->
   <div id="bg"></div>

   <!-- Scripts -->
   <script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
   <script src="<?=base_url('assets/js/browser.min.js')?>"></script>
   <script src="<?=base_url('assets/js/breakpoints.min.js')?>"></script>
   <script src="<?=base_url('assets/js/util.js')?>"></script>
   <script src="<?=base_url('assets/js/main.js')?>"></script>


</body>

</html>