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
      data-target="#exampleModal"
   >
      Add menu
   </button>
   <table class="table table-striped">
      <thead>
         <tr>
            <th scope="col">#</th>
            <th scope="col">Menu</th>
         </tr>
      </thead>
      <tbody>
         <?php $i = 1?>
         <?php foreach ($menu as $m): ?>
         <tr>
            <th scope="row"><?=$i?></th>
            <td><?=$m['menu']?></td>
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
   id="exampleModal"
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
            action="<?=base_url('menu')?>"
            method="post"
         >
            <div class="modal-body">
               <div class="form-group">
                  <label for="menu">Menu</label>
                  <input
                     type="text"
                     class="form-control"
                     id="menu"
                     name="menu"
                     placeholder="Enter menu"
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