<?php

namespace App\Models;

use App\Library\GoogleClient;
use Google\Service\Drive\DriveFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class File extends Model {

    use HasFactory, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'user_id',
        'folder_id',
        'name',
        'description',
        'password',
        'visible',
        'extension',
        'space_disk',
        'file',
        'url_visible',
        'drive_file_id'
    ];

    public function iconLabel() {

        switch($this->extension) {
            case 'zip': 
                return '<i class="ri-folder-zip-line"></i>';
                break;
            case 'xls': 
                return '<i class="ri-file-excel-2-line"></i>';
                break;
            case 'xlsx': 
                return '<i class="ri-file-excel-2-line"></i>';
                break;
            case 'ods': 
                return '<i class="ri-file-excel-2-line"></i>';
                break;
            case 'csv': 
                return '<i class="ri-file-excel-2-line"></i>';
                break;  
            case 'docx': 
                return '<i class="ri-file-word-2-line"></i>';
                break; 
            case 'docm ': 
                return '<i class="ri-file-word-2-line"></i>';
                break; 
            case 'dotx ': 
                return '<i class="ri-file-word-2-line"></i>';
                break; 
            case 'doc ': 
                return '<i class="ri-file-word-2-line"></i>';
                break; 
            default: 
                return '<i class="ri-file-fill"></i>';
                break;
        }
    }

    public function folder() {
        return $this->belongsTo(Folder::class);
    }

    // public function uploadToGoogleDrive(GoogleClient $googleClient) {

    //     $driveService = $googleClient->getDriveService();
        
    //     $filePath = Storage::path($this->file);

    //     $driveFile = new DriveFile();
    //     $driveFile->setName($this->name);
    //     $driveFile->setDescription($this->description);

    //     $content = file_get_contents($filePath);
    //     $uploadedFile = $driveService->files->create($driveFile, [
    //         'data' => $content,
    //         'mimeType' => mime_content_type($filePath),
    //         'uploadType' => 'multipart',
    //     ]);

    //     $this->drive_file_id = $uploadedFile->id;
    //     $this->save();

    //     return $uploadedFile;
    // } 

    public function uploadToGoogleDrive(Request $request, GoogleClient $googleClient) {       
        
        $googleClient->init();
    
        if (!$googleClient->authenticated()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $driveService = $googleClient->getDriveService();
    
        $filePath = Storage::path($this->file);

        $driveFile = new DriveFile();
        $driveFile->setName($this->name);
        $driveFile->setDescription($this->description);
    
        $content = file_get_contents($filePath);
    
        $uploadedFile = $driveService->files->create($driveFile, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
        ]);
    
        $this->drive_file_id = $uploadedFile->id;
        $this->save();
    
        return response()->json($uploadedFile);
    }
    

    protected static function boot() {

        parent::boot();

        static::deleting(function ($file) {
            
            if ($file->isForceDeleting()) {

                $filePath = storage_path('app/public/' . $file->file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        });
    }
}
