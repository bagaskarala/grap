<!-- Header -->
<?php
$this->load->view('templates/header', $title);
$this->load->view('templates/sidebar');
?>
<!-- Content Wrapper -->
<div
   id="content-wrapper"
   class="d-flex flex-column pb-3"
>

   <!-- Main Content -->
   <div id="content">

      <!-- Topbar -->
      <?php $this->load->view('templates/topbar', $user);?>

      <div id="app">
         <!-- Page -->
         <?php $this->load->view($page);?>
      </div>

   </div>
   <!-- End of Main Content -->

</div>
<!-- End of Content Wrapper -->

<!-- Footer -->
<?php $this->load->view('templates/footer');?>