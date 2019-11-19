<!-- Begin Page Content -->
<div class="container-fluid">
   <!-- <?=form_error('menu', ' <div class="alert alert-danger">', '</div>')?>
   <div class="alert alert-info">
      <?=$this->session->flashdata('message');?>
   </div> -->

   <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="<?=base_url('admin/role')?>"><i class="fa fa-angle-double-left"></i> Back to Role</a>
   </div>

   <h4>Role : <?=$role['role']?></h4>

   <table class="table table-striped">
      <thead>
         <tr>
            <th scope="col">#</th>
            <th scope="col">Menu</th>
            <th scope="col">Checklist</th>
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