<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <!-- Card -->
   <div
      class="card"
      style="width: 18rem;"
   >
      <img
         src="<?=base_url('assets/img/profile/') . $user['image'];?>"
         class="card-img"
      >
      <div class="card-body">
         <h5 class="card-title"><?=$user['name']?></h5>
         <p class="card-text"><?=$user['email']?></p>
         <a
            href="<?=base_url('user/edit')?>"
            class="btn btn-primary"
         >Edit</a>
      </div>
   </div>
</div>
<!-- /.container-fluid -->