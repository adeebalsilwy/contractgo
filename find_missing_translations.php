<?php
/**
 * Script to find and report missing translation keys
 */

// Load English labels
$enLabels = include __DIR__ . '/resources/lang/en/labels.php';
$arLabels = include __DIR__ . '/resources/lang/ar/labels.php';
$enValidation = include __DIR__ . '/resources/lang/en/validation.php';
$enPasswords = include __DIR__ . '/resources/lang/en/passwords.php';
$enMessages = include __DIR__ . '/resources/lang/en/messages.php';

echo "==============================================\n";
echo "Missing Translation Keys Report\n";
echo "==============================================\n\n";

// Check labels
echo "LABELS:\n";
echo "-------\n";
$missingLabels = array_diff_key($enLabels, $arLabels);
echo "Total English keys: " . count($enLabels) . "\n";
echo "Total Arabic keys: " . count($arLabels) . "\n";
echo "Missing Arabic keys: " . count($missingLabels) . "\n\n";

if (!empty($missingLabels)) {
    echo "Missing Keys:\n";
    foreach ($missingLabels as $key => $value) {
        echo "- {$key}: '{$value}'\n";
    }
    echo "\n";
}

// Check validation
echo "\nVALIDATION:\n";
echo "-----------\n";
$arValidationPath = __DIR__ . '/resources/lang/ar/validation.php';
if (file_exists($arValidationPath)) {
    $arValidation = include $arValidationPath;
    $missingValidation = array_diff_key($enValidation, $arValidation);
    echo "English validation keys: " . count($enValidation) . "\n";
    if (isset($arValidation['custom'])) {
        unset($arValidation['custom']);
    }
    echo "Arabic validation keys: " . count($arValidation) . "\n";
    echo "Missing: " . count($missingValidation) . "\n";
} else {
    echo "⚠️  File missing: resources/lang/ar/validation.php\n";
    echo "Total keys needed: " . count($enValidation) . "\n";
}

// Check passwords
echo "\nPASSWORDS:\n";
echo "----------\n";
$arPasswordsPath = __DIR__ . '/resources/lang/ar/passwords.php';
if (file_exists($arPasswordsPath)) {
    $arPasswords = include $arPasswordsPath;
    $missingPasswords = array_diff_key($enPasswords, $arPasswords);
    echo "English password keys: " . count($enPasswords) . "\n";
    echo "Arabic password keys: " . count($arPasswords) . "\n";
    echo "Missing: " . count($missingPasswords) . "\n";
} else {
    echo "⚠️  File missing: resources/lang/ar/passwords.php\n";
    echo "Total keys needed: " . count($enPasswords) . "\n";
}

// Check messages
echo "\nMESSAGES:\n";
echo "---------\n";
$arMessagesPath = __DIR__ . '/resources/lang/ar/messages.php';
if (file_exists($arMessagesPath)) {
    $arMessages = include $arMessagesPath;
    $missingMessages = array_diff_key($enMessages, $arMessages);
    echo "English message keys: " . count($enMessages) . "\n";
    echo "Arabic message keys: " . count($arMessages) . "\n";
    echo "Missing: " . count($missingMessages) . "\n";
} else {
    echo "⚠️  File missing: resources/lang/ar/messages.php\n";
    echo "Total keys needed: " . count($enMessages) . "\n";
}

echo "\n==============================================\n";
echo "Report Complete!\n";
echo "==============================================\n";
