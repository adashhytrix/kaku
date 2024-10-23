<!-- include header -->
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- /include header -->
<body id="page-top" class="lw-page-bg lw-public-master">
    <!-- Page Wrapper -->
    <div id="wrapper" class="container-fluid">
        <!-- include sidebar -->
        <?php if(isLoggedIn()): ?>
        <?php echo $__env->make('includes.public-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!-- /include sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column lw-page-bg">
            <div id="content">
                <!-- include top bar -->
                <?php if(isLoggedIn()): ?>
                <?php echo $__env->make('includes.public-top-bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
                <!-- /include top bar -->

                <!-- Begin Page Content -->
                <div class="lw-page-content">
                    <!-- header advertisement -->
                    <?php if(!getFeatureSettings('no_adds') and getStoreSettings('header_advertisement')['status'] == 'true'): ?>
                    <div class="lw-ad-block-h90 mt-5">
                        <?= getStoreSettings('header_advertisement')['content'] ?>
                    </div>
                    <?php endif; ?>
                    <!-- /header advertisement -->
                    <?php if(isset($pageRequested)): ?>
                    <?php echo $pageRequested; ?>
                    <?php endif; ?>
                    <!-- footer advertisement -->
                    <?php if(!getFeatureSettings('no_adds') and getStoreSettings('footer_advertisement')['status'] == 'true'): ?>
                    <div class="lw-ad-block-h90">
                        <?= getStoreSettings('footer_advertisement')['content'] ?>
                    </div>
                    <?php endif; ?>
                    <!-- /footer advertisement -->
                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <div class="lw-cookie-policy-container row p-4" id="lwCookiePolicyContainer">
        <div class="col-sm-11">
            <?php echo $__env->make('includes.cookie-policy', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-sm-1 mt-2"><button id="lwCookiePolicyButton" class="btn btn-primary"><?= __tr('OK') ?></button></div>
    </div>
    <!-- include footer -->
    <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- /include footer -->

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
                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?= __tr('Not now') ?></button>
                    <a class="btn btn-primary" href="<?= route('user.logout') ?>"><?= __tr('Logout') ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Logout Modal-->
</body>

<script>
    var response = jQuery.parseJSON('<?=bonusCreditNotification()?>');
    if(response.isAlreadyNotNotified == true){
        $('.credits-display-text').text(response.credits.credits);
            creditBadgeShow();
    }
</script>
</html><?php /**PATH C:\xampp\htdocs\api-kaku.jurysoft.in (2)\api-kaku.jurysoft.in\resources\views/public-master.blade.php ENDPATH**/ ?>