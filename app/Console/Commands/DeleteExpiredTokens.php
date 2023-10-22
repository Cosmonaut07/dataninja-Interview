<?php

namespace App\Console\Commands;

use App\Models\UserToken;
use Illuminate\Console\Command;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all expired tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserToken::where('expires_at', '<', now())->delete();
    }
}
