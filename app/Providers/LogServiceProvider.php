<?php

namespace App\Providers;

use App\Models\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

   

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Event::listen('eloquent.*', function ($eventName, array $data) {
        //     if(strpos($eventName, 'Log') !== false){
        //        return true;
        //     }

        //     if(strpos($eventName, 'eloquent.created') !== false){
        //        $this->saveLog($data, $eventName, 'created');
        //     }

        //     if(strpos($eventName, 'eloquent.updated') !== false){
        //         $this->saveLog($data, $eventName, 'updated');
        //     }

        //     if(strpos($eventName, 'eloquent.deleted') !== false){
        //         $this->saveLog($data, $eventName, 'deleted');
        //     }
        // });
    }

    private function discoverTableNameByEvent($eventName)
    {
        $class = substr($eventName, strpos($eventName, 'App'));
        return (new $class)->getTable();
    }

    private function saveLog($data, $eventName, $operation = '')
    {
        try {
            Log::create([
                'id_data'    => $data[0]->id,
                'table_name' => $this->discoverTableNameByEvent($eventName),
                'data'       => json_encode($data),
                'operation'  => $operation,
            ]);
        } catch (\Exception $e) {
            FacadesLog::info($e->getMessage());
        }
    }
}
