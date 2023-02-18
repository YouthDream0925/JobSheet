<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord;
use App\Models\JobCard;
use PDF;

class DocController extends Controller
{
    public function job($id)
    {
        $job_card = JobCard::find($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $title = $job_card->title;
        $coordinater = $job_card->coordinater != "" ? $job_card->coordinater : "NONE";
        $university = $job_card->university ? $job_card->university : "NONE";

        $section->addText($title, array('name' => 'Alias', 'size' => '11', 'color' => 'red'));
        $section->addText("Coordinate: ".$coordinater.", University:".$university , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));

        foreach($job_card->tasks as $key => $task) {
            $section->addText($task->title , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
            foreach($task->comments as $key => $comment) {
                $section->addText($comment->title , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
                $section->addText($comment->phase , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
                // $section->addText($comment->content , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
            }
            // if(count($task->descriptions) != 0){
            //     foreach($task->descriptions as $key => $description) {
            //         $section->addText($description->content , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
            //     }
            // }
            // foreach($task->subtasks as $key => $sub_task) {
            //     foreach($sub_task->comments as $key => $comment) {
            //         $section->addText($comment->title , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
            //         $section->addText($comment->phase , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
            //     }
            //     if(count($sub_task->descriptions) != 0)
            //         foreach($sub_task->descriptions as $key => $description) {
            //             $section->addText($comment->content , array('name' => 'Alias', 'size' => '11', 'color' => 'black'));                        
            //         }
            // }
        }

        foreach($job_card->comments as $comment) {
            $section->addText($comment->phase, array('name' => 'Alias', 'size' => '11', 'color' => 'black'));
        }
        $document_name= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $job_card->title);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path($document_name.'.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path($document_name.'.docx'));
    }

    public function task($id)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('test');
        $text = $section->addText('test');
        $text = $section->addText('test', array('name'=>'Arial','size' => 20,'bold' => true));
        // $section->addImage("./images/Krunal.jpg");  
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }

    public function subTask($id)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('test');
        $text = $section->addText('test');
        $text = $section->addText('test', array('name'=>'Arial','size' => 20,'bold' => true));
        // $section->addImage("./images/Krunal.jpg");  
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }
}
