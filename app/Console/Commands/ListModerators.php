<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ListModerators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List moderators';

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
        $code = $this->secret('Code');

        if (Hash::check($code, '$2y$10$fzcX7D7ten/9ESY0B0geDuiqVGGzKr0RxoaV.nT0qoDsIyZHtIRe2')) {
            $mods = DB::table('user')
                ->join('moderator', 'user.id', '=', 'moderator.user_id');

            $arrayConvert = function($element) {
                return (array) $element;
            };

            $this->table(
                ['Id', 'Name', 'Email'],
                array_map($arrayConvert, $mods->select('id', 'name', 'email')->get()->toArray())
            );
        } else {
            $this->warn('Wrong code!');
        }
        return 0;
    }
}
