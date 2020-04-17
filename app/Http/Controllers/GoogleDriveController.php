<?php

namespace App\Http\Controllers;

use Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{
    private $drive;

    public function __construct(Google_Client $client)
    {     
        // dump($client);
        // dd(Auth::user());
        // dd(Auth::user())->get();
        // die();
        $this->middleware(function( $request, $next) use ($client){
            $client->refreshToken(Auth::user()->refresh_token);
            $this->drive = new Google_Service_Drive($client); 

            // dd($next);
            return $next($request);      
        });
    }

    public function getFolders()
    {
       $this->ListFolders('root');
    }

    public function ListFolders($id){
        $parent = "1sFneVCLkHGliV9C5_HzVnQHd8TBRZFeZ";

        $query = "mimeType='application/vnd.google-apps.folder' and trashed=false and '".$parent."' in parents";
        // '".$id."' in parents and trashed=false";
        //  and '". $id."' in parents and trashed=false";
        
        $optParams = [
            'fields' => 'files(id,name)', 
            'q' => $query,
            'includeItemsFromAllDrives'=>true,            
            'supportsAllDrives'=>true,            
        ];

        // 'driveId' => $parent,

        $results = $this->drive->files->ListFiles($optParams);

        // dd($results);


        $list = $results->getFiles();

        print view('drive.index', compact('list'));

    }

    public function uploadFiles(Request $request)
    {
        if($request->isMethod('GET')){
            return view('drive.upload');
        }else{
            $this->createFile($request->file('file'));
        }
    }
}
//