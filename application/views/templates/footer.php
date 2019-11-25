</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a
   class="scroll-to-top rounded"
   href="#page-top"
>
   <i class="fas fa-angle-up"></i>
</a>

<!-- Vue dist files -->
<script src="<?=base_url('assets/dist/app.js')?>"></script>

<!-- Bootstrap core JavaScript-->
<script src="<?=base_url('assets/vendor/jquery/jquery.min.js')?>"></script>
<script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?=base_url('assets/vendor/jquery-easing/jquery.easing.min.js')?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?=base_url('assets/js/sb-admin-2.min.js')?>"></script>

<script>
// JQUERY SCRIPT
$('.custom-file-input').on('change', function() {
   let fileName = $(this).val().split('\\').pop();
   $(this).next('.custom-file-label').addClass("selected").html(fileName);
})

$('.access-checkbox').on('click', function() {
   const menuId = $(this).data('menu')
   const roleId = $(this).data('role')

   $.ajax({
      url: "<?=base_url('admin/role/change_access')?>",
      type: 'post',
      data: {
         menu_id: menuId,
         role_id: roleId
      },
      success: function() {
         document.location.href = "<?=base_url('admin/role/role_access/')?>" + roleId
      }
   })
})
</script>
</body>

</html>