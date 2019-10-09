<!-- Begin Page Content -->
<div class="container-fluid">
   <?=form_error('menu', ' <div class="alert alert-danger">', '</div>')?>
   <?=$this->session->flashdata('message');?>
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <!-- Button trigger modal -->
   <button
      type="button"
      class="btn btn-primary mb-3"
      data-toggle="modal"
      data-target="#roleModal"
   >
      Add menu
   </button>
   <table class="table table-striped">
      <thead>
         <tr>
            <th scope="col">#</th>
            <th scope="col">Role</th>
         </tr>
      </thead>
      <tbody>
         <?php $i = 1?>
         <?php foreach ($role as $r): ?>
         <tr>
            <th scope="row"><?=$i?></th>
            <td><?=$r['role']?></td>
            <td>
               <a
                  href="<?=base_url('admin/role_access/') . $r['id']?>"
                  class="badge badge-warning"
               >Access Role</a>
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

</div>
<!-- End of Main Content -->

<!-- Modal -->
<div
   class="modal fade"
   id="roleModal"
   tabindex="-1"
   role="dialog"
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
            action="<?=base_url('admin/role')?>"
            method="post"
         >
            <div class="modal-body">
               <div class="form-group">
                  <label for="role">Role</label>
                  <input
                     type="text"
                     class="form-control"
                     id="role"
                     name="role"
                     placeholder="Enter role"
                  >
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