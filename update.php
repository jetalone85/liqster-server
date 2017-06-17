<?php

echo sprintf(" \r\n");
echo sprintf("========================================== \r\n");
echo sprintf("========= LIQSTER.PL UPDATE TOOL ========= \r\n");
echo sprintf("========================================== \r\n");

rmdir(__DIR__ . '/vendor');
exec('composer install');
