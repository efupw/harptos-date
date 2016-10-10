<?php
require_once 'src/HarptosDate.php';

$d = \EFUPW\FR\HarptosDate::yearOffsetByDays(1391, 1);
echo $d->getDate(), PHP_EOL;
