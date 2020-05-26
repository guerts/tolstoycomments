<?php

return array(
    'apikey' => array(
        'title' => 'Ключ доступа к API',
        'value' => '',
        'control_type' => 'text',
        'description' => '',
    ),
    'siteid' => array(
        'title' => 'Site ID',
        'value' => '',
        'control_type' => 'text',
        'description' => '',
    ),
    'allow_export' => array(
        'title' => 'Включить экспорт комментариев',
        'value' => '',
        'control_type' => 'checkbox',
        'description' => '<br>Задание CRON <br> <b>'.'*/5 * * * * /usr/bin/php -q '.wa()->getConfig()->getPath('root').DIRECTORY_SEPARATOR.'cli.php blog tolstoyPluginExport'.'</b><br><br>',
    ),
    'export_latest_id' => array(
        'title' => 'lastId',
        'value' => '',
        'control_type' => 'hidden',
        'description' => '',
    ),
    'allow_hook' => array(
        'title' => 'Использовать хук frontend_post.footer',
        'value' => '',
        'control_type' => 'checkbox',
        'description' => '<br>Хэлпер: <br> <b>{blogTolstoyPlugin::display($post)}</b><br><br>Счетчик комментариев: <br> <b>{blogTolstoyPlugin::count($post)}</b><br><br>',
    ),
);
//EOF
