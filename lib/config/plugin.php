<?php

return array(
    'name'        => 'TolstoyComments',
    'description' => '',
    'vendor'      => 975294,
    'version'     => '1.0.0',
    'img'         => 'img/tc.png',
    'frontend'    => true,
    'handlers'  => array(
        'frontend_post'=> 'frontendPost',
    ),
    'settings' => array(
        'apikey' => array(
            'title' => 'Ключ доступа к API',
            'value' => '',
            'settings_html_function' => 'text',
            'description' => '',
        ),
        'siteid' => array(
            'title' => 'Site ID',
            'value' => '',
            'settings_html_function' => 'text',
            'description' => '',
        ),
        'allow_export' => array(
            'title' => 'Включить экспорт комментариев',
            'value' => '',
            'settings_html_function' => 'checkbox',
            'description' => '<br>Задание CRON <br> <b>'.'*/5 * * * * /usr/bin/php -q '.wa()->getConfig()->getPath('root').DIRECTORY_SEPARATOR.'cli.php blog tolstoyPluginExport'.'</b><br><br>',
        ),
        'export_latest_id' => array(
            'title' => 'lastId',
            'value' => '',
            'settings_html_function' => 'hidden',
            'description' => '',
        ),
        'allow_hook' => array(
            'title' => 'Использовать хук frontend_post.footer',
            'value' => '',
            'settings_html_function' => 'checkbox',
            'description' => '<br>Хэлпер: <br> <b>{blogTolstoyPlugin::display($post)}</b><br><br>Счетчик комментариев: <br> <b>{blogTolstoyPlugin::count($post)}</b><br><br>',
        ),
        
    ),
);
//EOF
