<?php
changeAppLocale();
// re-init configs for translations
config([
    '__tech' => require config_path('__tech.php'),
    '__settings' => require config_path('__settings.php'),
]);