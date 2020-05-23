<?php
return array(
    'blog_tolstoy' => array(
        'comment_id' => array('int', 'null' => 0),
        'message' => array('text', 'null' => 0),
        'visible' => array('int', 11, 'null' => 0),
        'name' => array('text', 'null' => 0),
        'email' => array('text', 'null' => 0),
        'ip' => array('text', 'null' => 0),
        'datetime' => array('datetime', 'null' => 0),
        'rating' => array('int', 1, 'null' => 0),
        'url' => array('text', 'null' => 0),
        'subscribe' => array('text', 'null' => 'N'),
        'parent' => array('int', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'comment_id',
        ),
    ),	
);