<?php

class blogTolstoyPluginExportCli extends waCliController {

    public function execute() {
        $export = new blogTolstoyExport();
        $result = $export->init();
    }
}