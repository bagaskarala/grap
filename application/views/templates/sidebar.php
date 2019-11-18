<!-- Query  -->
<?php
$role_id    = $this->session->userdata('role_id');
$query_menu = "SELECT `menu`.`id`, `menu`
               FROM `menu` JOIN `access_menu`
               ON `menu`.`id` = `access_menu`.`menu_id`
               WHERE `access_menu`.`role_id` = $role_id
               ORDER BY `access_menu`.`menu_id` ASC";

$menu = $this->db->query($query_menu)->result_array();
?>

<!-- Sidebar -->
<ul
   class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
   id="accordionSidebar"
>

   <!-- Sidebar - Brand -->
   <a
      class="sidebar-brand d-flex align-items-center justify-content-center"
      href="index.html"
   >
      <div class="sidebar-brand-icon rotate-n-15">
         <i class="fas fa-shield"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Grap</div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Looping Menu -->
   <?php foreach ($menu as $m): ?>
   <div class="sidebar-heading">
      <?=$m['menu']?>
   </div>

   <?php
$menu_id       = $m['id'];
$query_submenu = "SELECT * FROM `sub_menu`WHERE `menu_id` = $menu_id AND `is_active` = 1";

$sub_menu = $this->db->query($query_submenu)->result_array();
?>

   <?php foreach ($sub_menu as $sm): ?>
   <?php if ($title == $sm['title']): ?>
   <li class="nav-item active">
      <?php else: ?>
   <li class="nav-item">
      <?php endif;?>

      <a
         class="nav-link pb-0"
         href="<?=base_url($sm['url'])?>"
      >
         <i class="<?=$sm['icon']?>"></i>
         <span><?=$sm['title']?></span></a>
   </li>
   <?php endforeach?>

   <!-- Divider -->
   <hr class="sidebar-divider my-0 my-3">

   <?php endforeach?>


   <!-- Nav Item - Pages Collapse Menu -->
   <!-- <li class="nav-item">
      <a
         class="nav-link collapsed"
         href="#"
         data-toggle="collapse"
         data-target="#collapseTwo"
         aria-expanded="true"
         aria-controls="collapseTwo"
      >
         <i class="fas fa-fw fa-cog"></i>
         <span>Components</span>
      </a>
      <div
         id="collapseTwo"
         class="collapse"
         aria-labelledby="headingTwo"
         data-parent="#accordionSidebar"
      >
         <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Components:</h6>
            <a
               class="collapse-item"
               href="buttons.html"
            >Buttons</a>
            <a
               class="collapse-item"
               href="cards.html"
            >Cards</a>
         </div>
      </div>
   </li> -->

   <!-- Sidebar Toggler (Sidebar) -->
   <div class="text-center d-none d-md-inline">
      <button
         class="rounded-circle border-0"
         id="sidebarToggle"
      ></button>
   </div>

</ul>
<!-- End of Sidebar -->