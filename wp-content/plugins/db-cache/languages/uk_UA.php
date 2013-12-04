<?php

/*
Version 0.5
*/

$dbc_labels['configuration'] = "Налаштування";

$dbc_labels['activate'] = "Увімкнути";

$dbc_labels['timeout'] = "Час життя кешу";
$dbc_labels['timeout_desc'] = "хвилин(и). <em>(Застарілі файли видаляються автоматично)</em>";

$dbc_labels['tablesfilter'] = "Таблиці для кешування";

$dbc_labels['saved'] = "Налаштування збережено.";
$dbc_labels['cantsave'] = "Налаштування не може бути збережено. Будь-ласка <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">встановіть права запису</a> для файла <u>config.ini</u>";
$dbc_labels['activated'] = "Кешування увімкнено.";
$dbc_labels['notactivated'] = "Кешування не ввімкнено. Будь-ласка <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">встановіть права запису</a> для папки <u>wp-content</u>";
$dbc_labels['deactivated'] = "Кешування вимкнено. Всі файли кешу видалені.";
$dbc_labels['cleaned'] = "Файли кешу видалені.";
$dbc_labels['expiredcleaned'] = "Застарілі файли кешу видалені.";

$dbc_labels['additional'] = "Додаткові параметри";
$dbc_labels['loadstat'] = "Шаблон виводу статистики";
$dbc_labels['loadstattemplate'] = "<!-- Згенеровано за {timer} секунд. Виконано {queries} запитів до БД та {cached} кешованих запитів. Використано {memory} пам'яті -->";
$dbc_labels['loadstatdescription'] = "Налаштування показу статистики використання ресурсів в футері вашого шаблону. Щоб вимкнути - залиште це поле порожнім.<br/>
{timer} - час генерації, {queries} - кількість запитів, {cached} - кешовані запити, {memory} - пам`ять";

$dbc_labels['save'] = "Зберегти";
$dbc_labels['clear'] = "Очистити кеш";
$dbc_labels['clearold'] = "Видалити застарілі файли кешу";

?>