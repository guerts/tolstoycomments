<?php

class blogTolstoyPluginModel extends waModel
{
    protected $table = 'blog_tolstoy';
    
    public function getComments($url) {
        $sql = "SELECT * FROM `{$this->table}` WHERE url = '".$url."' AND visible = 1 ORDER BY 'datetime' DESC";
        $comments = $this->query($sql)->fetchAll();
        return array_reverse($comments);
    }
    
    public function exportComments($data = array())
    {
        $comments = $this->getAll('comment_id');
        $array_keys = array_keys($comments);
        $array = array();
        foreach ($data as $d) {
            if (!in_array($d['comment_id'], $array_keys)) {
                $array[] = $d;
            }
        }
        $this->multipleInsert($array);
    }
    
    public function updateComments($data = array())
    {
	    if (is_array($data) && !empty($data)) {
	        $array_key = array();
            $sql = "UPDATE ".$this->table." SET visible = CASE comment_id";
            foreach ($data as $d) {
                $array_key[] = $d['comment_id'];
                $sql = $sql." WHEN {$d['comment_id']} THEN '".$d['visible']."'";
            }
            $sql = $sql." END, message = CASE comment_id";
            foreach ($data as $d) {
                $sql = $sql." WHEN {$d['comment_id']} THEN '".$d['message']."'";
            }
            $sql = $sql." END WHERE comment_id IN (".implode(',', array_values($array_key)).")";
            $model = new waModel();
            $model->exec($sql);
	    }
    }
}