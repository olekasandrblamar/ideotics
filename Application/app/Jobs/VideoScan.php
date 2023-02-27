<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\FileEntry;
use App\Models\AdminNotification;

class VideoScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->data;
        $fileEntry = FileEntry::where('id', $id)->userEntry()->notExpired()->with(['user', 'storageProvider'])->firstOrFail();
        try {
            if($fileEntry->storageProvider->symbol == 's3'){
                $fileEntry = FileEntry::find($id);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://dev3.backstories.io/run/predict',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"data":["' . 's3://ideoticsv2/' . $fileEntry->path . '",10,"aW191OwmDG97JZizLgY0MQbHBJ4TJN7R"]}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                ));

                $response = curl_exec($curl);
                file_put_contents('/1.txt', $response.env('Scan_Video_Url'));
                curl_close($curl);
                $response = json_decode($response);
                if($response->data[0]->status == 'success'){

                    $fileEntry->scan_status = 1;
                    $fileEntry->duration = $response->duration;
                    $fileEntry->save();
                    $title = $fileEntry->filename . __(' is scanned successfully.') ;
                    $image = asset('images/settings/guests.png');
                    $link = '/admin/uploads/users/' . $fileEntry->shared_id . '/view';
                    $notify = new AdminNotification();
                    $notify->title = $title;
                    $notify->image = $image;
                    $notify->link = $link;
                    $notify->save();
                }
            }else{

            }
        } catch (\Exception$e) {

        }
    }
}
