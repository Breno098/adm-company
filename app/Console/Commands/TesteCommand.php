<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class TesteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificação e criação de coluns company_id em tabelas';

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
        $times = [
            ['start' => '12:30', 'end' => '14:30', 'result' => 'Não pode'],
            ['start' => '11:00', 'end' => '13:00', 'result' => 'Pode'],
            ['start' => '15:00', 'end' => '16:00', 'result' => 'Pode'],
            ['start' => '09:00', 'end' => '12:00', 'result' => 'Não pode'],
            ['start' => '14:10', 'end' => '15:00', 'result' => 'Pode'],
            ['start' => '13:10', 'end' => '15:00', 'result' => 'Não pode'],
            ['start' => '10:00', 'end' => '15:00', 'result' => 'Não pode'],
            ['start' => '15:10', 'end' => '18:00', 'result' => 'Pode'],
        ];

        foreach ($times as $time) {
            $resultCheck =  DB::select( DB::raw("
                select count(1) as count
                from appointments
                where company_id = 1
                    and
                        (
                            ( '{$time['start']}' > time(time_start) and '{$time['start']}' < time(time_end))
                            or
                            ( '{$time['end']}' > time(time_start) and '{$time['end']}' < time(time_end))
                        )
                    or
                        (
                            ( time(time_start) > '{$time['start']}'  and time(time_start) < '{$time['end']}')
                            or
                            ( time(time_end) > '{$time['start']}' and time(time_end) < '{$time['end']}')
                        )
                ")
            );

            $whereString = "
                ( (? > time(time_start) and ? < time(time_end)) or ( ? > time(time_start) and ? < time(time_end)) )
                or
                ( (time(time_start) > ?  and time(time_start) < ?) or ( time(time_end) > ? and time(time_end) < ?) )
            ";

            $result = Appointment::selectRaw('count(1) as count')
                ->whereRaw($whereString, [
                    $time['start'],
                    $time['start'],
                    $time['end'],
                    $time['end'],
                    $time['start'],
                    $time['end'],
                    $time['start'],
                    $time['end'],
                ])
                ->get();

            dd($resultCheck, $result);
            // dd($result->toSql());
        }

        return 0;
    }
}
