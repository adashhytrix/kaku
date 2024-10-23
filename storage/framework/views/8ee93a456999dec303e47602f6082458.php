<!-- include header -->
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- /include header -->
<?php
$isLcOk = true;
?>
<body id="page-top" class="lw-admin-section">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- include sidebar -->
        <?php if(isLoggedIn()): ?>
        <?php echo $__env->make('includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!-- /include sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column bg-gray-900">
            <div id="content">
                <!-- include top bar -->
                <?php if(isLoggedIn()): ?>
                <?php echo $__env->make('includes.top-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
                <!-- /include top bar -->
                <!-- Begin Page Content -->
                <div class="container-fluid lw-page-content">
                <?php if(getStoreSettings('product_registration', 'registration_id')): ?>
                    <?php echo $__env->make('configuration.licence-information', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php
                        $isLcOk = true;
                    ?>
                <?php elseif(sha1(
                    array_get($_SERVER, 'HTTP_HOST', '') .
                    getStoreSettings('product_registration', 'registration_id')) == getStoreSettings('product_registration', 'signature')): ?>
                    <?php echo $__env->make('configuration.licence-information', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php
                        $isLcOk = true;
                    ?>
                <?php elseif(isset($pageRequested)): ?>
                    <?php echo $pageRequested; ?>
                    <?php endif; ?>
                </div>
                <!-- /.container-fluid -->
            </div>

            <!-- include footer -->
            <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- /include footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- /Scroll to Top Button-->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= __tr('Ready to Leave?') ?></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= __tr('Select "Logout" below if you are ready to end your current session.') ?>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?= __tr('Cancel') ?></button>
                    <a class="btn btn-primary" href="<?= route('user.logout') ?>"><?= __tr('Logout') ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Logout Modal-->
    <?php if(!$isLcOk): ?>
    <script>
            $(document).on('lw_events_ajax_start_replace', function(){
                location.reload();
            });
    </script>
    <?php endif; ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\api-kaku.jurysoft.in (2)\api-kaku.jurysoft.in\resources\views/manage-master.blade.php ENDPATH**/ ?>