<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-dark topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <strong>
        <?= __tr('Admin Section') ?>
    </strong>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        
        <?php $translationLanguages = getActiveTranslationLanguages(); ?>
        <!-- Language Menu -->
        
        <!-- Language Menu -->

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 ml-2 d-none d-lg-inline text-gray-600 small"><?= getUserAuthInfo('profile.full_name') ?></span>
                <img class="img-profile rounded-circle" src="<?= getUserAuthInfo('profile.profile_picture_url') ?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= route('manage.dashboard', ['username' => getUserAuthInfo('profile.username')]) ?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __tr('Profile') ?>
                </a>
                <?php
                /*
                <a class="dropdown-item" href="<?= route('user.change_password') ?>">
                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __tr('Change Password') ?>
                </a>
                */

                ?>
                 <?php
                 /*
                <a class="dropdown-item" href="<?= route('user.change_email') ?>">
                    <i class="fas fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __tr('Change Email') ?>
                </a>
                */

                ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __tr('Logout') ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->

<!-- for image gallery -->
<?php echo $__env->make('includes.image-gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/includes/top-bar.blade.php ENDPATH**/ ?>