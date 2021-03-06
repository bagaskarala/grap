<!-- Begin Page Content -->
<div class="container-fluid">
   <?=$this->session->flashdata('message');?>

   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <div class="row">
      <div class="col-md-6">
         <?=form_open_multipart('profile/edit')?>
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
            >Name</label>
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
         <div class="form-group row">
            <div class="col-sm-2">Foto</div>
            <div class="col-sm-10">
               <div class="row">
                  <div class="col-sm-4">
                     <img
                        src=" <?=base_url('assets/img/profile/') . $user['image']?> "
                        class="img-thumbnail"
                     >
                  </div>
                  <div class="col-sm-8">
                     <div class="custom-file">
                        <input
                           type="file"
                           class="custom-file-input"
                           id="image"
                           name="image"
                           for="image"
                        >
                        <label
                           class="custom-file-label"
                           for="customFile"
                        >Choose file</label>
                     </div>
                  </div>
               </div>

            </div>

         </div>
         <div class="form-group row justify-content-end">
            <div class="col-sm-10">
               <button
                  type="submit"
                  class="btn btn-primary"
               >Save</button>
            </div>
         </div>
         </form>
      </div>
   </div>

</div>
<!-- /.container-fluid -->