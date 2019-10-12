<!-- Begin Page Content -->
<div class="container-fluid">
   <?php if (validation_errors()): ?>
   <div class="alert alert-danger">
      <?=validation_errors()?>
   </div>
   <?php endif?>

   <?=$this->session->flashdata('message');?>
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <!-- Button trigger modal -->
   <button
      type="button"
      class="btn btn-primary mb-3"
      data-toggle="modal"
      data-target="#submenuModal"
   >
      Add submenu
   </button>
   <table class="table table-striped">
      <thead>
         <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Menu</th>
            <th scope="col">Url</th>
            <th scope="col">Icon</th>
            <th scope="col">Active</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         <?php $i = 1?>
         <?php foreach ($submenu as $sm): ?>
         <tr>
            <th scope="row"><?=$i?></th>
            <td><?=$sm['title']?></td>
            <td><?=$sm['menu']?></td>
            <td><?=$sm['url']?></td>
            <td><i class="<?=$sm['icon']?>"></i></td>
            <td><?=$sm['is_active'] == 1 ? 'Yes' : 'No'?></td>
            <td>
               <a
                  href=""
                  class="badge badge-primary"
               >Edit</a>
               <a
                  href=""
                  class="badge badge-danger"
               >Delete</a>
            </td>
         </tr>
         <?php $i++?>
         <?php endforeach;?>
      </tbody>
   </table>

</div>
<!-- /.container-fluid -->

<!-- Modal -->
<div
   class="modal fade"
   id="submenuModal"
   tabindex="-1"
   role="dialog"
   aria-labelledby="exampleModalLabel"
   aria-hidden="true"
>
   <div
      class="modal-dialog"
      role="document"
   >
      <div class="modal-content">
         <div class="modal-header">
            <h5
               class="modal-title"
               id="exampleModalLabel"
            >Add Menu</h5>
            <button
               type="button"
               class="close"
               data-dismiss="modal"
               aria-label="Close"
            >
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form
            action="<?=base_url('menu/submenu')?>"
            method="post"
         >
            <div class="modal-body">
               <div class="form-group">
                  <label for="title">Title</label>
                  <input
                     type="text"
                     class="form-control"
                     id="title"
                     name="title"
                     placeholder="Enter title"
                  >
               </div>
               <div class="form-group">
                  <label for="title">Select Menu</label>
                  <select
                     name="menu_id"
                     id="menu_id"
                     class="form-control"
                  >
                     <option value="">Select Menu</option>
                     <?php foreach ($menu as $m): ?>
                     <option value="<?=$m['id']?>"><?=$m['menu']?></option>
                     <?php endforeach?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="url">URL</label>
                  <input
                     type="text"
                     class="form-control"
                     id="url"
                     name="url"
                     placeholder="Enter url"
                  >
               </div>
               <div class="form-group">
                  <label for="icon">Icon</label>
                  <input
                     type="text"
                     class="form-control"
                     id="icon"
                     name="icon"
                     placeholder="Enter icon"
                  >
               </div>
               <div class="form-group">
                  <div class="form-check">
                     <input
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        value="1"
                        checked
                     >
                     <label
                        for="is_active"
                        class="form-check-label"
                     >Active?</label>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button
                  type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal"
               >Close</button>
               <button
                  type="submit"
                  class="btn btn-primary"
               >Add</button>
            </div>
         </form>
      </div>
   </div>
</div>