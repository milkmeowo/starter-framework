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
