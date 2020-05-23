<?php

class blogTolstoyExport
{
	protected $apiKey;
	protected $siteId;
	
	/**
	 * Export constructor.
	 */
	public function __construct()
	{
	    $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
		$this->apiKey = $settings['apikey'];
		$this->siteId = $settings['siteid'];
	}
	
	/**
	 * Initialize import process
	 * @return bool|Export
	 */
	public static function init()
	{
	    $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
		$active = $settings['allow_export'];
		if ($active != 1) {
			return false;
		}
		$lastId = $settings['export_latest_id'];
		$export = new static();
		$export->handle($lastId);
		return $export;
	}
	
	/**
	 * Main method. Export comments from Tolstoycomments if process enabled
	 * @param $lastId
	 * @return bool
	 * @throws \Exception
	 */
	public function handle($lastId)
	{
		if (!$this->apiKey || !$this->siteId) {
			$this->resetProcess();
			return false;
		}
		$lastId = is_numeric($lastId) && $lastId > 0 ? $lastId : '';
		$url = "https://api.tolstoycomments.com/api/export/$this->apiKey/site/$this->siteId/comment/$lastId";
		$response = file_get_contents($url);
		$result = json_decode($response);
		
		if (!is_object($result) || !isset($result->data) || count($result->data->comments) == 0) {
			$this->resetProcess();
			return false;
		}
		
		$comments = array();
		foreach ($result->data->comments as $i) {
			$url = parse_url($i->chat->url, PHP_URL_PATH);
			if (!isset($url)) {
				continue;
			}
			$date = new DateTime($i->datеtime);
			$date = $date->format('Y-m-d H:i:s');
			//waLog::log(print_r($date), 'tolstoy.log');
			$comment = array(
			    'comment_id' => $i->id,
				'message' => $i->message,
				'visible' => (int) $i->visible,
				'name' => $i->user->name,
				'email' => $i->user->email,
				'ip' => $i->ip,
				'datetime' => $date,
				'rating' => $i->rating,
				'url' => $url,
				'subscribe' => 'N',
				'parent' => 0
			);
			$comments[] = $comment;
		}
		$model = new blogTolstoyPluginModel();
		$model->exportComments($comments);
		$model->updateComments($comments);
		
		if (is_numeric($result->data->comment_last_id)) {
		    $this->setNextTask($result->data->comment_last_id);
		} else {
		    $this->resetProcess();
		}
		return true;
	}
	
	/**
	 * Delete deleted from Tolstoycomments comments in database
	 */
	private function cleanDeletedComments()
	{
	    /*
	    global $wpdb;
		$ids = $wpdb->get_col($wpdb->prepare(
			"select `comments`.`comment_ID` from $wpdb->comments as comments
			left join $wpdb->commentmeta as meta on (`comments`.`comment_ID` = `meta`.`comment_id`)
			where `meta`.`meta_key` = `_tolstoycomments_updated` and `meta`.`meta_value` is not null and `meta`.`meta_value` < %d
			limit 50",
			time() - 604800));
		foreach ($ids as $i) {
			wp_delete_comment($i, true);
		}
		*/
	}
	
	/**
	 * Set next task
	 * @param int $latestId
	 */
	private function setNextTask($latestId)
	{
	    $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
        $settings['export_latest_id'] = $latestId;
        $plugin->saveSettings($settings);
	    /*
		update_option('tolstoycomments_export_latest_id', $latestId, false);
		wp_clear_scheduled_hook('tolstoycomments_cron_task_queue');
		wp_schedule_event(time() + 60 * 5, 'daily', 'tolstoycomments_cron_task_queue');
		*/
	}
	
	/**
	 * Reset task if get error or export ended
	 * @throws \Exception
	 */
	private function resetProcess()
	{
	    $plugin = wa('blog')->getPlugin('tolstoy');
        $settings = $plugin->getSettings();
        unset($settings['export_latest_id']);
        $plugin->saveSettings($settings);
	    /*
		delete_option('tolstoycomments_export_latest_id');
		wp_clear_scheduled_hook('tolstoycomments_cron_task_queue');
		wp_clear_scheduled_hook('tolstoycomments_cron_task');
		wp_schedule_event(Plugin::getTaskTime(), 'daily', 'tolstoycomments_cron_task');
		*/
	}
	
}