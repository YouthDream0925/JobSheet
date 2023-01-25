<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /********** Controller and View Global Variable **********/
        $setting = Setting::where('type', 'notification')->get();
        if(count($setting) != 0)
            $notification_allow = $setting[0]->value;
        else
            $notification_allow = 0;

        $status = [
            '' => 'Status',
            '0' => 'Pending',
            '1' => 'Active',
            '2' => 'Complete',
            '3' => 'Uncomplte'
        ];

        $reverse_status = [
            'Status' => '',
            'Pending' => '0',
            'Active' => '1',
            'Complete' => '2',
            'Uncomplte' => '3'
        ];

        $special = [
            '0' => 'No',
            '1' => 'Yes',
        ];

        $per_page= 60;

        /********** Controller Global Variable **********/
        $job_card_per_page = 15;
        config(['pagination.per_page' => $per_page, 'pagination.job_card_per_page' => $job_card_per_page, 'setting.notification_allow' => $notification_allow, 'status' => $status, 'special' => $special, 'reverse_status' => $reverse_status]);

        /********** View Global Variable**********/
        $pages = [
            '15' => 15,
            '30' => 30,
            '60' => 60
        ];

        $phase = [
            '' =>  'Project of Stage',
            '0' => 'Semester 1',
            '1' => 'Semester 2',
            '2' => 'Semester 3',
            '3' => 'Semester 4',
            '4' => 'Semester 5',
            '5' => 'Semester 6',
        ];

        $global_phase = [
            '' =>  'Project of Stage',
            '0' => 'Semester 1',
            '1' => 'Semester 2',
            '2' => 'Semester 3',
            '3' => 'Semester 4',
            '4' => 'Semester 5',
            '5' => 'Semester 6',
        ];

        View::share(['status' => $status, 'phase' => $phase, 'global_phase' => $global_phase, 'notification_allow' => $notification_allow, 'special' => $special, 'pages' => $pages, 'per_page' => $per_page]);

        /********* URL Global Variable **********/
        \Blade::directive('svg', function($arguments) {
            // Funky madness to accept multiple arguments into the directive
            list($path, $class) = array_pad(explode(',', trim($arguments, "() ")), 2, '');
            $path = trim($path, "' ");
            $class = trim($class, "' ");
    
            // Create the dom document as per the other answers
            $svg = new \DOMDocument();
            $svg->load(public_path($path));
            $svg->documentElement->setAttribute("class", $class);
            $output = $svg->saveXML($svg->documentElement);
    
            return $output;
        });
    }
}
