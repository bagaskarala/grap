<!-- Begin Page Content -->
<div class="container-fluid">
   <?=$this->session->flashdata('message');?>

   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <div class="row">
      <div class="col">
         <form
            action="<?=base_url('user/edit')?>"
            method="post"
         >

            <div class="form-group row">
               <label
                  for="email"
                  class="col-sm-2 col-form-label"
               >Email</label>
               <div class="col-sm-10">
                  <input
                     readonly
                     type="text"
                     class="form-control"
                     id="email"
                     name="email"
                     value="<?=$user['email']?>"
                  >
               </div>
            </div>
            <div class="form-group row">
               <label
                  for="name"
                  class="col-sm-2 col-form-label"
               >name</label>
               <div class="col-sm-10">
                  <input
                     type="text"
                     class="form-control"
                     id="name"
                     name="name"
                     value="<?=$user['name']?>"
                  >
                  <?=form_error('name', '<small class="text-danger">', '</small>')?>
               </div>
            </div>
            <button
               type="submit"
               class="btn btn-primary"
            >Submit</button>
         </form>
      </div>
   </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->