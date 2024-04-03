<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

use App\Models\FormData;
use App\Models\Error;
use App\Models\Target;

use App\Helpers\DataSEOHelper;

class ResponseTargetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'response:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TestResponce';

    private function output($message)
    {
        $this->info($message);
        Log::info($message);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output('Start Responced!');
        $items = FormData::where('status', 'not_processed')->get();
        foreach ($items as $item) {
            $meesage = 'Responce from data: ' . "\n"
                . 'Target: ' . $item->target . "\n"
                .  'Exclude Targets' . $item->exclude_targets;

            $this->output($meesage);
           
            $responce = new DataSEOHelper();

            $data = $responce->getBacklinks(
                [
                    [
                        "targets" => [
                            "1" => $item->target,
                        ],
                        "exclude_targets" => json_decode($item->exclude_targets, true)
                    ]
                ]
            );

            switch ($data['status']) {
                case 'Error':
                    $this->output('Error, save logs to db!');
                    $data['body']['data_id'] = $item->id;
                    Error::insert($data['body']);
                    break;
                case 'Ok':
                    $this->output('Done, save to db!');
                    
                    $chunks = array_chunk($data['body']->toArray(), 50);
                    foreach ($chunks as $chunk) {
                        Target::insert($chunk);
                    }
                    break;
            }
            $formData = FormData::find($item->id);

            $formData->status = "processed";

            $formData->save();

            $this->info("\n");
        }
        $this->output('Completed!');
    }
}
