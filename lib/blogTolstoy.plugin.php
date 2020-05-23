<?php

class blogTolstoyPlugin extends blogPlugin
{
    public static function count($post)
    {
        $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
        return self::getComments($post['link'], $settings['siteid'], true);
    }
    
    public static function getComments($url, $site_id, $calc_count = false)
    {
        $model = new blogTolstoyPluginModel();
        $comments = $model->getComments(parse_url($url, PHP_URL_PATH));
        $view = wa()->getView();
        $view->assign('comments', $comments);
        $view->assign('site_id', $site_id);
        $template = 'commentsBlock.html';
        if ($calc_count) {
            $template = 'commentsCount.html';
        }
        return $view->fetch(wa()->getAppPath('plugins/tolstoy/templates/'.$template, 'blog'));
    }

    public function frontendPost($params)
    {
        $settings = $this->getSettings();
        if ($settings['allow_hook']) {
            return array(
                'footer' => self::getComments($params['link'], $settings['siteid']),
            );
        }
    }
    
    public static function display($post)
    {
        $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
        return self::getComments($post['link'], $settings['siteid']);
    }
}