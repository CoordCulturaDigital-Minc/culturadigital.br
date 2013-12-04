<?php

/*
Version 0.6
*/

$dbc_labels['configuration'] = "Настройка";

$dbc_labels['activate'] = "Включить";

$dbc_labels['timeout'] = "Время жизни кеша";
$dbc_labels['timeout_desc'] = "минут(ы). <em>(Устаревшые файлы удаляются автоматически)</em>";

$dbc_labels['saved'] = "Настройки сохранены.";
$dbc_labels['cantsave'] = "Настройки не могут быть сохранены. Пожалуйста <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">установите права записи</a> для файла <u>config.ini</u>";
$dbc_labels['activated'] = "Кеширование включено.";
$dbc_labels['notactivated'] = "Кеширование не включено. Пожалуйста <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">установите права записи</a> для папки <u>wp-content</u>";
$dbc_labels['deactivated'] = "Кеширование выключено. Все файлы кеша удалены.";
$dbc_labels['cleaned'] = "Файлы кеша удалены.";
$dbc_labels['expiredcleaned'] = "Устарелые файлы кеша удалены.";

$dbc_labels['additional'] = "Дополнительные параметры";
$dbc_labels['filter'] = "Фильтр кеша";
$dbc_labels['filterdescription'] = "Не кешировать запросы. Разделяйте фильтры с помощью '|' (вертикальная линия, '_posts|_postmeta')";
$dbc_labels['loadstat'] = "Шаблон вывода статистики";
$dbc_labels['loadstattemplate'] = "<!-- Сгенерировано за {timer} секунд. Сделано {queries} запросов к БД и {cached} запросов из кеша. Использовано {memory} памяти -->";
$dbc_labels['loadstatdescription'] = "Настройки отображения статистики использования ресурсов в футере вашего шаблона. Чтобы отключить - оставьте это поле пустым.<br/>
{timer} - время генерации, {queries} - количество запросов, {cached} - запросы из кеша, {memory} - память";

$dbc_labels['save'] = "Сохранить";
$dbc_labels['clear'] = "Очистить кеш";
$dbc_labels['clearold'] = "Удалить устарелые файлы кеша";

?>