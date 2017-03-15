<?php
/**
 * CommandServiderProvider.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Milkmeowo\Framework\Base\Console\Commands\KeyGenerateCommand;

class CommandServiderProvider extends ServiceProvider
{
    public function register()
    {
        /* key:generate */
        $this->commands(KeyGenerateCommand::class);
    }
}
