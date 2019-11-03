<!DOCTYPE html>
<html lang="en">

<head>
   <title><?=$title?></title>
   <meta charset="utf-8">
   <meta
      http-equiv="X-UA-Compatible"
      content="IE=edge"
   >
   <!-- Load SBAdmin bootstrap -->
   <link
      href="<?=base_url('assets/css/sb-admin-2.min.css')?>"
      rel="stylesheet"
   >
   <!-- Load custom login style -->
   <link
      rel="stylesheet"
      href="<?=base_url('assets/css/auth_main.css')?>"
   />
   <noscript>
      <link
         rel="stylesheet"
         href="<?=base_url('assets/css/auth_noscript.css')?>"
      />
   </noscript>
</head>

<body class="is-preload">
   <div id="wrapper">
      <!-- Menampilkan halaman tertentu -->
      <?php $this->load->view($page)?>

      <!-- Footer -->
      <footer id="footer"></footer>
   </div>

   <!-- Background -->
   <div id="bg"></div>

   <!-- Login scripts -->
   <script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
   <script src="<?=base_url('assets/js/browser.min.js')?>"></script>
   <script src="<?=base_url('assets/js/breakpoints.min.js')?>"></script>
   <script src="<?=base_url('assets/js/auth_util.js')?>"></script>
   <script src="<?=base_url('assets/js/auth_main.js')?>"></script>
</body>

</html>