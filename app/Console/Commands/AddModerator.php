<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Moderator;
use Illuminate\Support\Facades\DB;

class AddModerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promote {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promotes user to moderator';

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
            $user = DB::table('user')
                ->where('name', $username)
                ->first();

            $moderator = DB::table('moderator')
                ->join('user', 'user.id', '=', 'moderator.user_id')
                ->where('name', $username)
                ->first();

            if (isset($user) && !isset($moderator)) {
                DB::table('moderator')->insert([
                    'user_id' => $user->id
                ]);
                $this->info('User promoted successfully');  
            } else if (isset($moderator)) {
                $this->warn($username . ' is already moderator!');
            } else {
                $this->warn('Could not find user with username ' . $username . '!');
            }
        } else {
            $this->warn('Wrong code!');
        }

        return 0;
    }
}
