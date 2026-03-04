<?php

/**
 * Script to find missing translation keys between English and Arabic language files
 * and generate a report
 */

// Load English labels
$enLabels = include __DIR__ . '/resources/lang/en/labels.php';
$arLabels = include __DIR__ . '/resources/lang/ar/labels.php';

// Find missing keys in Arabic
$missingKeys = array_diff_key($enLabels, $arLabels);

echo "==============================================\n";
echo "Missing Translation Keys Analysis\n";
echo "==============================================\n\n";

echo "Total English keys: " . count($enLabels) . "\n";
echo "Total Arabic keys: " . count($arLabels) . "\n";
echo "Missing Arabic keys: " . count($missingKeys) . "\n\n";

if (!empty($missingKeys)) {
    echo "Missing Keys List:\n";
    echo "----------------------------------------------\n";
    foreach ($missingKeys as $key => $value) {
        echo "- {$key}\n";
    }
    echo "\n";
}

// Find keys that exist in both but might need better translation
$commonKeys = array_intersect_key($enLabels, $arLabels);
echo "Common keys: " . count($commonKeys) . "\n\n";

// Check for English text still present in Arabic file (indicates incomplete translation)
$untranslatedKeys = [];
foreach ($commonKeys as $key => $enValue) {
    $arValue = $arLabels[$key];
    // If Arabic value contains mostly English characters, it might be untranslated
    if (preg_match('/^[a-zA-Z\s\.\,\/\-_]+$/u', $arValue) && strlen($arValue) > 3) {
        $untranslatedKeys[$key] = [
            'en' => $enValue,
            'ar' => $arValue
        ];
    }
}

if (!empty($untranslatedKeys)) {
    echo "Potentially Untranslated Keys (still in English):\n";
    echo "----------------------------------------------\n";
    foreach ($untranslatedKeys as $key => $values) {
        echo "- {$key}: '{$values['ar']}' (should be translated from '{$values['en']}')\n";
    }
    echo "\n";
}

echo "Analysis Complete!\n";
