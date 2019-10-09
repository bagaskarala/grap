<!-- Begin Page Content -->
<div class="container-fluid">
   <?=form_error('menu', ' <div class="alert alert-danger">', '</div>')?>
   <?=$this->session->flashdata('message');?>
   <!-- Page Heading -->
   <h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>

   <h4>Role : <?=$role['role']?></h4>
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
               <div class="form-check">
                  <input
                     class="form-check-input access-checkbox"
                     type="checkbox"
                     <?=check_access($role['id'], $m['id'])?>
                     data-role="<?=$role['id']?>"
                     data-menu="<?=$m['id']?>"
                  >
               </div>
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