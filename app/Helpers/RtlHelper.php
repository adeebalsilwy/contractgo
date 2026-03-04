<?php

if (!function_exists('is_rtl')) {
    /**
     * Check if current language is RTL (Right-to-Left)
     * 
     * @return bool
     */
    function is_rtl()
    {
        $rtlLanguages = ['ar', 'fa', 'he', 'ur']; // Arabic, Persian, Hebrew, Urdu
        
        $currentLocale = session('my_locale', app()->getLocale());
        
        return in_array($currentLocale, $rtlLanguages);
    }
}

if (!function_exists('get_text_direction')) {
    /**
     * Get text direction based on current locale
     * 
     * @return string 'rtl' or 'ltr'
     */
    function get_text_direction()
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('get_text_align')) {
    /**
     * Get text alignment based on current locale
     * 
     * @return string 'right' or 'left'
     */
    function get_text_align()
    {
        return is_rtl() ? 'right' : 'left';
    }
}

if (!function_exists('get_opposite_direction')) {
    /**
     * Get opposite direction (for margins, paddings, etc.)
     * 
     * @param string $direction 'start' or 'end'
     * @return string 'left' or 'right'
     */
    function get_opposite_direction($position = 'start')
    {
        $isRtl = is_rtl();
        
        if ($position === 'start') {
            return $isRtl ? 'right' : 'left';
        }
        
        return $isRtl ? 'left' : 'right';
    }
}
