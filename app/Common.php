<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

/**
 * Custom base_url with cache busting
 * Adds filemtime as query parameter to force browser cache refresh
 *
 * @param string $uri
 * @param string|null $protocol
 * @return string
 */
if (!function_exists('asset_url')) {
    function asset_url(string $uri = '', ?string $protocol = null): string
    {
        $baseURL = rtrim(config('App')->baseURL, '/');
        $filepath = FCPATH . ltrim($uri, '/');

        // Check if file exists and add cache busting
        if (file_exists($filepath)) {
            $mtime = filemtime($filepath);
            $uri .= (strpos($uri, '?') !== false ? '&' : '?') . 'v=' . $mtime;
        }

        return $baseURL . '/' . ltrim($uri, '/');
    }
}

/**
 * CSS asset URL with cache busting
 *
 * @param string $uri
 * @return string
 */
if (!function_exists('css_url')) {
    function css_url(string $uri = ''): string
    {
        return asset_url($uri);
    }
}

/**
 * JS asset URL with cache busting
 *
 * @param string $uri
 * @return string
 */
if (!function_exists('js_url')) {
    function js_url(string $uri = ''): string
    {
        return asset_url($uri);
    }
}
