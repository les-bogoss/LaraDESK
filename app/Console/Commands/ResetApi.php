<?php

namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Console\Command;

class ResetApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:reset_api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset API token to user who not logged for 1 week';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all user where not logged for 1 week
        $users = User::where('last_login', '<', now()->subWeek())->get();
        foreach ($users as $user) {
            $user->api_token = Str::random(60);
            $user->save();
            $this->info('API token reset for'.$user->email.'successful');
        }
        return 0;
    }
}
