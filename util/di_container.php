<?php

use Cognetif\TinyImg\CronManager;
use Cognetif\TinyImg\Manager;
use Cognetif\TinyImg\Queue;
use Cognetif\TinyImg\RunLogger;
use Cognetif\TinyImg\TinyApi;
use Cognetif\TinyImg\Util\Icon;
use Cognetif\TinyImg\Util\SettingHelper;


$di['PerchScheduledTasks'] = function () {
    return new PerchScheduledTasks ();
};

$di['PerchUtil'] = function () {
    return new PerchUtil();
};

$di['PerchApi'] = function () {
    return new PerchAPI(1.0, 'cognetif_tinyimg');
};

$di['Icon'] = function () {
    $iconData = require(__DIR__ . '/icons.php');
    return new Icon($iconData);
};

$di['SettingsHelper'] = function ($c) {
    return new SettingHelper($c['PerchApi']);
};

$di['TinyApi'] = function ($c) {
    return new TinyApi($c['SettingsHelper']);
};

$di['Queue'] = function ($c) {
    return new Queue($c['SettingsHelper'], $c['TinyApi'], $c['PerchUtil']);
};

$di['Manager'] = function ($c) {
    return new Manager($c['SettingsHelper'], $c['Queue'], $c['TinyApi'], $c['PerchUtil']);
};

$di['RunLogger'] = function () {
    return new RunLogger();
};

$di['CronManager'] = function ($c) {
    return new CronManager($c['SettingsHelper'], $c['PerchUtil'], $c['Manager'],$c['RunLogger'], $c['PerchScheduledTasks']);
};
