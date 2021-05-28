<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RemoveModerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demote {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demotes user from moderator';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = $this->argument('username');
        $code = $this->secret('Code');

        if (Hash::check($code, '$2y$10$fzcX7D7ten/9ESY0B0geDuiqVGGzKr0RxoaV.nT0qoDsIyZHtIRe2')) {
            $moderator = DB::table('moderator')
                ->join('user', 'user.id', '=', 'moderator.user_id')
                ->where('name', $username);

            if (isset($moderator)) {
                $moderator->delete();
                $this->info('Moderator demoted successfully');  
            } else {
                $this->warn('Could not find moderator with username ' . $username);
            }
        } else {
            $this->warn('Wrong code!');
        }

        return 0;
    }
}
