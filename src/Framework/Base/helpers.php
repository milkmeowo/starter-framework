<?php
/**
 * helpers.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

if ( ! function_exists('is_lumen')) {
    /**
     * Checks whether or not the application is Lumen
     *
     * @return bool
     */
    function is_lumen()
    {
        $version = app()->version();
        $is_lumen = str_contains($version, 'Lumen');

        return $is_lumen;
    }
}
if ( ! function_exists('is_laravel')) {
    /**
     * Checks whether or not the application is Laravel
     *
     * @return bool
     */
    function is_laravel()
    {
        return ! is_lumen();
    }
}

if ( ! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return app('path').( $path ? DIRECTORY_SEPARATOR.$path : $path );
    }
}

if ( ! function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->make($value, $options);
    }
}

if ( ! function_exists('smart_get_client_ip')) {

    /**
     * @return array|string
     */
    function smart_get_client_ip()
    {
        $request = app('request');
        $clientIp = $request->header('X-Client-Ip');
        if (empty( $clientIp )) {
            $clientIp = $request->getClientIp(true);
        }

        return $clientIp;
    }
}
