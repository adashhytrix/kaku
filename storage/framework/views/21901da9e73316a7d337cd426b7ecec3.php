<?php $__env->startSection('page-title', __tr('Login')); ?>
<?php $__env->startSection('head-title', __tr('Login')); ?>
<?php $__env->startSection('keywordName', strip_tags(__tr('Login'))); ?>
<?php $__env->startSection('keyword', strip_tags(__tr('Login'))); ?>
<?php $__env->startSection('description', strip_tags(__tr('Login'))); ?>
<?php $__env->startSection('keywordDescription', strip_tags(__tr('Login'))); ?>
<?php $__env->startSection('page-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('twitter-card-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('page-url', url()->current()); ?>

<!-- include header -->
<?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- /include header -->

<body>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <a href="<?= url(''); ?>">
                                            <img class="lw-logo-img" src="<?= getStoreSettings('logo_image_url') ?>" alt="<?= getStoreSettings('name') ?>">
                                        </a>
                                        <hr class="mt-4 mb-4">
                                        <h4 class="text-gray-200 mb-4"><?= __tr('Login to your account')  ?></h4>
                                        <?php if(session('errorStatus')): ?>
                                        <!--  success message when email sent  -->
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <?= session('message') ?>
                                        </div>
                                        <!--  /success message when email sent  -->
                                        <?php endif; ?>
                                    </div>
                                    <!-- login form -->
                                    <form class="user lw-ajax-form lw-form" data-callback="onLoginCallback" method="post" action="<?= route('user.login.process') ?>" data-show-processing="true" data-secured="true">
                                        <!-- email input field -->
                                        <div class="form-group">
                                            <label for="lwEmail"><?= __tr('Username/Email') ?><?php if(getStoreSettings('allow_user_login_with_mobile_number')): ?>/<span title="<?php echo e(__tr('Use mobile number with country code with 0 prefix')); ?>"><?php echo e(__tr('Mobile Number')); ?></span><?php endif; ?></label>
                                            <input type="text" class="form-control d-block" name="email_or_username" aria-describedby="emailHelp"
                                            required>
                                        </div>
                                        <!-- / email input field -->

                                        <!-- password input field -->
                                        <div class="form-group">
                                            <label for="lwPassword"><?= __tr('Password') ?></label>
                                            <input type="password" class="form-control d-block" name="password" required minlength="6">
                                        </div>
                                        <!-- password input field -->

                                        <!-- remember me input field -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="rememberMeCheckbox" name="remember_me">
                                                <label class="custom-control-label text-gray-200" for="rememberMeCheckbox"><?= __tr('Remember Me')  ?></label>
                                            </div>
                                        </div>
                                        <!-- remember me input field -->
                                        <?php if(getStoreSettings('allow_recaptcha')): ?>
                                        <div class="form-group text-center">
                                            <div class="g-recaptcha d-inline-block" data-sitekey="<?php echo e(getStoreSettings('recaptcha_site_key')); ?>"></div>
                                        </div>
                                        <?php endif; ?>

                                        <!-- login button -->
                                        <button type="submit" value="Login" class="lw-ajax-form-submit-action btn btn-primary btn-user btn-block"><?= __tr('Login')  ?></button>
                                        <!-- / login button -->
                                        <!-- social login links -->
                                        <?php if(getStoreSettings('allow_google_login')): ?>
                                        <a href="<?= route('social.user.login', [getSocialProviderKey('google')]) ?>" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> <?= __tr('Login with Google')  ?>
                                        </a>
                                        <?php endif; ?>
                                        <?php if(getStoreSettings('allow_facebook_login')): ?>
                                        <a href="<?= route('social.user.login', [getSocialProviderKey('facebook')]) ?>" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> <?= __tr('Login with Facebook')  ?>
                                        </a>
                                        <?php endif; ?>
                                        <!-- social login links -->
                                     <!-- / login form -->
                                    <?php if(getStoreSettings('enable_otp_Login') && getStoreSettings('use_enable_sms_settings')): ?>
                                    <hr class="my-4">
                                    <!-- Login With OTP button -->
                                    <div class="text-center mt-3">
                                        <a href="<?= route('login.with.otp') ?>" class="btn btn-secondary btn-user btn-block"></i> <?= __tr('Login with OTP')  ?></a>
                                    </div>
                                    <!-- / Login With OTP button -->
                                <?php endif; ?>
                                    </form>
                                    <!-- forgot password button -->

                                    <?php
                                    /*
                                    <div class="text-center mt-3">
                                        <a class="small" href="<?= route('user.forgot_password') ?>"><?= __tr('Forgot Password?')  ?></a>
                                    </div>
                                    */
                                    ?>
                                    <!-- / forgot password button -->
                                    <hr class="mt-4 mb-3">
                                    <!-- create account button -->
                                    <?php
                                    /*
                                    <div class="text-center">
                                        <p><?= __tr("Don't have a Account? Create one its Free!!") ?></p>
                                        <a class="btn btn-success btn-lg btn-block-on-mobile" href="<?= route('user.sign_up') ?>"><?= __tr('Create an Account!')  ?></a>
                                    </div>
                                    */
                                    ?>
                                    <!-- / create account button -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <?php if(!request()->ajax()): $__env->startPush('appScripts'); endif; ?>
<script>
     var recaptchaInstance = "<?= getStoreSettings('allow_recaptcha') ?>";
    //on login success callback
    function onLoginCallback(response) {
        //check reaction code is 1 and intended url is not empty
        if (response.reaction == 1 && !_.isEmpty(response.data.intendedUrl)) {
            //redirect to intendedUrl location
            _.defer(function() {
                window.location.href = response.data.intendedUrl;
            })
        }
        if(recaptchaInstance){
            grecaptcha.reset();
        }
    }
</script>
<?php if(!request()->ajax()): $__env->stopPush(); endif; ?>
<!-- include footer -->
<!-- Footer -->

<!-- End of Footer -->

<!-- Messenger Dialog -->
<div class="modal fade" id="messengerDialog" tabindex="-1" role="dialog" aria-labelledby="messengerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button id="lwChatSidebarToggle" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <h5 class="modal-title">
                    <?= __tr('Chat') ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= __tr('Close') ?>"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="lwChatDialogLoader" style="display: none;">
                    <div class="d-flex justify-content-center m-5">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">
                                <?= __tr('Loading...') ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div id="lwMessengerContent"></div>
            </div>
        </div>
    </div>
</div>
<!-- Messenger Dialog -->
<img src="<?= asset('imgs/ajax-loader.gif') ?>" style="height:1px;width:1px;">
<div class="col-sm-10 text-center" id="bonusCreditsImg" style="display: none">
    <img class="mx-auto d-block" src="<?= asset('imgs/credits_payment_profit.png') ?>"
        alt="loader" style="height: 200px;">
        <h2 class="credits-display-text" style=""></h2>
</div>

<script>
    window.appConfig = {
        debug: "<?= config('app.debug') ?>",
        csrf_token: "<?= csrf_token() ?>",
        locale: "<?= config('app.locale') ?>"
    }
</script>

<?= __yesset([
    'dist/js/confetti.js',
    'dist/pusher-js/pusher.min.js',
    'dist/js/vendorlibs-public.js',
    'dist/js/vendorlibs-datatable.js',
    'dist/js/vendorlibs-photoswipe.js',
    'dist/js/vendorlibs-smartwizard.js',
    'dist/push-js/push.min.js'
], true) ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js" integrity="sha512-7DIA0YtDMlg4BW1e0pXjd96R5zJwK8fJullbvGWbuvkCYgEMkME0UFfeZIGfQGYjSwCSeFRG5MIB5lhhEvKheg==" crossorigin="anonymous"></script>
<?php echo $__env->yieldPushContent('footer'); ?>
<script>
    (function() {
        $.validator.messages = $.extend({}, $.validator.messages, {
            required: '<?= __tr("This field is required.") ?>',
            remote: '<?= __tr("Please fix this field.") ?>',
            email: '<?= __tr("Please enter a valid email address.") ?>',
            url: '<?= __tr("Please enter a valid URL.") ?>',
            date: '<?= __tr("Please enter a valid date.") ?>',
            dateISO: '<?= __tr("Please enter a valid date (ISO).") ?>',
            number: '<?= __tr("Please enter a valid number.") ?>',
            digits: '<?= __tr("Please enter only digits.") ?>',
            equalTo: '<?= __tr("Please enter the same value again.") ?>',
            maxlength: $.validator.format('<?= __tr("Please enter no more than {0} characters.") ?>'),
            minlength: $.validator.format('<?= __tr("Please enter at least {0} characters.") ?>'),
            rangelength: $.validator.format('<?= __tr("Please enter a value between {0} and {1} characters long.") ?>'),
            range: $.validator.format('<?= __tr("Please enter a value between {0} and {1}.") ?>'),
            max: $.validator.format('<?= __tr("Please enter a value less than or equal to {0}.") ?>'),
            min: $.validator.format('<?= __tr("Please enter a value greater than or equal to {0}.") ?>'),
            step: $.validator.format('<?= __tr("Please enter a multiple of {0}.") ?>')
        });
    })();
</script>
<?= __yesset([
    'dist/js/common-app*.js'
], true) ?>
<script>
    __Utils.setTranslation({
        'processing': "<?= __tr('processing') ?>",
        'uploader_default_text': "<span class='filepond--label-action'><?= __tr('Drag & Drop Files or Browse') ?></span>",
        'gif_no_result': "<?= __tr('Result Not Found') ?>",
        "message_is_required": "<?= __tr('Message is required') ?>",
        "sticker_name_label": "<?= __tr('Stickers') ?>",
        "chat_placeholder": "<?= __tr('type message...') ?>",
        "search_gif": "<?= __tr('Search GIF') ?>",
        "send_gif": "<?= __tr('Send GIF') ?>"
    });

    var userLoggedIn = '<?= isLoggedIn() ?>',
        enablePusher = '<?= getStoreSettings('allow_pusher') ?>',
        isAdmin = '<?= isAdmin() ?>';

    if (userLoggedIn && enablePusher) {
        var userUid = '<?= getUserUID() ?>',
            pusherAppKey = '<?= getStoreSettings('pusher_app_key') ?>',
            __pusherAppOptions = {
                cluster: '<?= getStoreSettings('pusher_app_cluster_key') ?>',
                forceTLS: true,
            };
            var channelId2 = '';
            if(isAdmin == true){
               channelId2 = '<?= configItem('admin_receiver_channel') ?>';
            }

    }
</script>
<?php if(isLoggedIn()): ?>
<!-- Include Audio Video Call Component -->
<?php echo $__env->make('messenger.audio-video', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- /Include Audio Video Call Component -->
<!-- caller ringtone -->
<audio id="lwMessageAlertTone">
	<source src="<?= asset('/imgs/audio/message-tone.mp3'); ?>" type="audio/mpeg">
</audio>
<!-- /caller ringtone -->
<script>
    //check user loggedIn or not
    if (userLoggedIn && enablePusher) {
        //if messenger dialog is open then hide new message dot
        $("#messengerDialog").on('click', function() {
            var messengerDialogVisibility = $("#messengerDialog").is(':visible');
            if (messengerDialogVisibility) {
                $(".lw-new-message-badge").hide();
            }
        });

        //subscribe pusher notification
        subscribeNotification('event.user.notification', pusherAppKey, userUid, null, function(responseData) {
            //get notification list
            var requestData = responseData.getNotificationList,
                getNotificationList = requestData.notificationData,
                getNotificationCount = requestData.notificationCount;
            //update notification count
            __DataRequest.updateModels({
                'totalNotificationCount': getNotificationCount, //total notification count
            });
            //check is not empty
            if (!_.isEmpty(getNotificationList)) {
                var template = _.template($("#lwNotificationListTemplate").html());
                $("#lwNotificationContent").html(template({
                    'notificationList': getNotificationList,
                }));
            }
            //check is not empty
            if (responseData) {
                switch (responseData.type) {
                    case 'user-likes':
                        if (responseData.showNotification != 0) {
                            showSuccessMessage(responseData.message);
                        }
                        break;
                    case 'user-gift':
                        if (responseData.showNotification != 0) {
                            showSuccessMessage(responseData.message);
                        }
                        break;
                    case 'profile-visitor':
                        if (responseData.showNotification != 0) {
                            showSuccessMessage(responseData.message);
                        }
                        break;
                    case 'user-login':
                        if (responseData.showNotification != 0) {
                            showSuccessMessage(responseData.message);
                        }
                        break;
                    default:
                        showSuccessMessage(responseData.message);
                        break;
                }
            }
        });
        if (!Push.Permission.has()) {
            Push.Permission.request();
        }

    navigator.serviceWorker.register("<?php echo e(asset('dist/push-js/serviceWorker.min.js')); ?>");
        var isWindowTabActive = true;
        $(window).on("blur focus", function(e) {
            var prevType = $(this).data("prevType");
            if (prevType != e.type) {   //  reduce double fire issues
                switch (e.type) {
                    case "blur":
                        isWindowTabActive = false;
                        break;
                    case "focus":
                        isWindowTabActive = true;
                        break;
                }
            }
            $(this).data("prevType", e.type);
        });

        subscribeNotification('event.user.chat.messages', pusherAppKey, userUid, channelId2, function(responseData) {
            var messengerDialogVisibility = $("#messengerDialog").is(':visible');
            //if messenger dialog is not open then show notification dot
            if (!messengerDialogVisibility) {
                $(".lw-new-message-badge").show();
            }
            // Message chat
            if (responseData.requestFor == 'MESSAGE_CHAT') {
                if(!isAdmin){

                    if (currentSelectedUserUid == responseData.toUserUid) {
                        __Messenger.appendReceivedMessage(responseData.type, responseData.message, responseData.createdOn);
                    }
                    // Set user message count
                    if (responseData.userId != currentSelectedUserId) {
                        var incomingMsgEl = $('.lw-incoming-message-count-' + responseData.userId),
                        messageCount = 1;
                        if (!_.isEmpty(incomingMsgEl.text())) {
                            messageCount = parseInt(incomingMsgEl.text()) + 1;
                        }

                        incomingMsgEl.text(messageCount);
                        $('.lw-messenger-contact-list .list-group.list-group-flush').prepend($('a.lw-user-chat-list#' + responseData.userId));
                        $('a.lw-user-chat-list#' + responseData.userId +' .lw-contact-status').removeClass('lw-away lw-offline').addClass('lw-online');
                    }

                    // Show notification of incoming messages
                    if (!messengerDialogVisibility && responseData.showNotification) {
                        showSuccessMessage(responseData.notificationMessage);
                    }
                }
                if(isAdmin){
                    if (currentSelectedUserUid == responseData.toUserUid && optionalLoggedInUserId == responseData.receiverUserId) {
                        __Messenger.appendReceivedMessage(responseData.type, responseData.message, responseData.createdOn);
                    }
                    // Set user message count
                    if (responseData.userId != currentSelectedUserId) {

                        var incomingMsgForUser = $('.lw-incoming-message-count-' + responseData.userId),
                        personalMessageCount = 1;
                        if (!_.isEmpty(incomingMsgForUser.text())) {
                            personalMessageCount = parseInt(incomingMsgForUser.text()) + 1;
                        }

                        incomingMsgForUser.text(personalMessageCount);
                        $('.lw-messenger-contact-list .list-group.list-group-flush').prepend($('a.lw-user-chat-list#' + responseData.userId));
                        $('a.lw-user-chat-list#' + responseData.userId +' .lw-contact-status').removeClass('lw-away lw-offline').addClass('lw-online');
                    }

                    if(optionalLoggedInUserId != responseData.receiverUserId){
                        var incomingMsgEl = $('.lw-fake-user-incoming-message-count-' + responseData.receiverUserId),
                        messageCount = 1;
                        if (!_.isEmpty(incomingMsgEl.text())) {
                            messageCount = parseInt(incomingMsgEl.text()) + 1;
                        }

                        incomingMsgEl.text(messageCount);
                    }

                    $('.lw-fake-user-messenger-list .list-group.list-group-flush').prepend($('a.lw-fake-user-chat-list#' + responseData.receiverUserId));
                    $('a.lw-fake-user-chat-list#' + responseData.receiverUserId +' .lw-contact-status').removeClass('lw-away lw-offline').addClass('lw-online');

                    // Show notification of incoming messages
                    if (!messengerDialogVisibility && responseData.showNotification) {
                        showSuccessMessage(responseData.notificationMessage);
                    }
                }
            }

            // Message request
            if (responseData.requestFor == 'MESSAGE_REQUEST') {

                if(!isAdmin){
                    if (responseData.userId == currentSelectedUserId) {
                        handleMessageActionContainer(responseData.messageRequestStatus, false);
                        if (!_.isEmpty(responseData.message)) {
                            __Messenger.appendReceivedMessage(responseData.type, responseData.message, responseData.createdOn);
                        }
                    } else {
                        // Show notification of incoming messages
                        if (!messengerDialogVisibility && responseData.showNotification) {
                            showSuccessMessage(responseData.notificationMessage);
                        }
                    }
                }
                else if(isAdmin){
                    if (responseData.userId == currentSelectedUserId && optionalLoggedInUserId == responseData.receiverUserId) {
                        handleMessageActionContainer(responseData.messageRequestStatus, false);
                        if (!_.isEmpty(responseData.message)) {
                            __Messenger.appendReceivedMessage(responseData.type, responseData.message, responseData.createdOn);
                        }
                    } else {
                        // Show notification of incoming messages
                        if (!messengerDialogVisibility && responseData.showNotification) {
                            showSuccessMessage(responseData.notificationMessage);
                        }
                    }
                }
            }

            if ((!messengerDialogVisibility || !isWindowTabActive) && _.get(responseData, 'showNotification', true)) {
                // play notification message alert
                $("#lwMessageAlertTone")[0].play();
                // check for the push notifications
                // Push.clear();
                if (!isWindowTabActive) {
                    Push.create("<?php echo e(__tr('New Message Received')); ?>", {
                        body: _.get(responseData, 'notificationMessage', "<?php echo e(__tr('__siteName__ message alert!', [
                            '__siteName__' => getStoreSettings('name')
                        ])); ?>"),
                        icon: "<?php echo e(getStoreSettings('small_logo_image_url')); ?>",
                        // timeout: 4000,
                        onClick: function () {
                            window.focus();
                            this.close();
                        }
                    });
                }
            }
        });

        subscribeNotification('event.user.credit', pusherAppKey, userUid, null, function(responseData) {
            // console.log(responseData);
            $('.credits-display-text').text(responseData.credits);
            if(responseData.messageType == 'success'){
                __DataRequest.get("<?= route('update.log') ?>", {}, function(responseData) {});
            }
            $("#lwTotalCreditWalletAmt").html(parseInt($("#lwTotalCreditWalletAmt").text()) + parseInt(responseData.credits));
            creditBadgeShow();
        });
    };

    //for cookie terms 
    function showCookiePolicyDialog() {
        if (__Cookie.get('cookie_policy_terms_accepted') != '1') {
            $('#lwCookiePolicyContainer').show();
        } else {
            $('#lwCookiePolicyContainer').hide();
        }
    };

    showCookiePolicyDialog();

    $("#lwCookiePolicyButton").on('click', function() {
        __Cookie.set('cookie_policy_terms_accepted', '1', 1000);
        showCookiePolicyDialog();
    });

    // Get messenger chat data
    function getChatMessenger(url, isAllChatMessenger) {
        var $allMessageChatButtonEl = $('#lwAllMessageChatButton'),
            $lwMessageChatButtonEl = $('#lwMessageChatButton');
        // check if request for all messenger 
        if (isAllChatMessenger) {
            var isAllMessengerChatLoaded = $allMessageChatButtonEl.data('chat-loaded');
            if (!isAllMessengerChatLoaded) {
                $allMessageChatButtonEl.attr('data-chat-loaded', true);
                $lwMessageChatButtonEl.attr('data-chat-loaded', false);
                fetchChatMessages(url);
            }
        } else {
            var isMessengerLoaded = $lwMessageChatButtonEl.data('chat-loaded');
            if (!isMessengerLoaded) {
                $lwMessageChatButtonEl.attr('data-chat-loaded', true);
                $allMessageChatButtonEl.attr('data-chat-loaded', false);
                fetchChatMessages(url);
            }
        }
    };

    // Fetch messages from server
    function fetchChatMessages(url) {
        $('#lwChatDialogLoader').show();
        $('#lwMessengerContent').hide();
        __DataRequest.get(url, {}, function(responseData) {
            $('#lwChatDialogLoader').hide();
            $('#lwMessengerContent').show();
        });
    };
</script>
<?php endif; ?>
<script>
    $.extend( $.fn.dataTable.defaults, {
                "language"        : {
                    "decimal":        "",
                    "emptyTable":     '<?= __tr("No data available in table") ?>',
                    "info":           '<?= __tr("Showing _START_ to _END_ of _TOTAL_ entries") ?>',
                    "infoEmpty":      "<?= __tr('Showing 0 to 0 of 0 entries') ?>",
                    "infoFiltered":   "<?= __tr('(filtered from _MAX_ total entries)') ?>",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "<?= __tr('Show _MENU_ entries') ?>",
                    "loadingRecords": "<?= __tr('Loading...') ?>",
                    "processing":     '<?= __tr("Processing...") ?>',
                    "search":         "<?= __tr('Search:') ?>",
                    "zeroRecords":    "<?= __tr('No matching records found') ?>",
                    "paginate": {
                        "first":      "<?= __tr('First') ?>",
                        "last":       "<?= __tr('Last') ?>",
                        "next":      "<?= __tr('Next') ?>",
                        "previous":   "<?= __tr('Previous') ?>"
                    },
                    "aria": {
                        "sortAscending":  "<?= __tr(': activate to sort column ascending') ?>",
                        "sortDescending": "<?= __tr(': activate to sort column descending') ?>"
                    }
                    }
            });
</script>
<?php echo $__env->yieldPushContent('appScripts'); ?>
<script defer src="https://unpkg.com/alpinejs@3.12.2/dist/cdn.min.js"></script>
<!-- /include footer --><?php /**PATH C:\xampp\htdocs\api-kaku.jurysoft.in (2)\api-kaku.jurysoft.in\resources\views/user/login.blade.php ENDPATH**/ ?>