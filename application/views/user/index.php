<!-- Begin Page Content -->
<div class="container-fluid">

   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <!-- Card -->
   <div class="row">

      <div
         class="card mb-3"
         style="max-width: 540px;"
      >
         <div class="row no-gutters">
            <div class="col-md-3">
               <img
                  src="<?=base_url('assets/img/profile/') . $user['image'];?>"
                  class="card-img"
               >
            </div>
            <div class="card-body">
               <h5 class="card-title"><?=$user['name']?></h5>
               <p class="card-text"><?=$user['email']?></p>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- /.container-fluid -->