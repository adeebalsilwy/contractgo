<?php
$en = include 'resources/lang/en/labels.php';
$ar = include 'resources/lang/ar/labels.php';

echo "Checking for 'select_priority':\n";
echo "English has it: " . (isset($en['select_priority']) ? 'YES' : 'NO') . "\n";
echo "Arabic has it: " . (isset($ar['select_priority']) ? 'YES' : 'NO') . "\n";

if (isset($en['select_priority'])) {
    echo "English value: " . $en['select_priority'] . "\n";
}
