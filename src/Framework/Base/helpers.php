<?php
/**
 * helpers.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return app('path').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('smart_get_client_ip')) {

    /**
     * @return array|string
     */
    function smart_get_client_ip()
    {
        $request = app('request');
        $clientIp = $request->header('X-Client-Ip');
        if (empty($clientIp)) {
            $clientIp = $request->getClientIp(true);
        }

        return $clientIp;
    }
}
