<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Storage;
use Config;

trait StoreImageTrait  {

    /**
     * Does very basic image validity checking and stores it. Redirects back if somethings wrong.
     * @Notice: This is not an alternative to the model validation for this field.
     *
     * @param Request $request
     * @return $this|false|string
     */
    public function verifyAndStoreImage( Request $request, $fieldname = 'image', $directory = 'unknown' ) {

        if( $request->hasFile( $fieldname ) ) {

            if (!$request->file($fieldname)->isValid()) {
               return response()->json(['status'=>422,'message'=>'invalid Image']);

            }
    
            $path = 'public/'.$directory;
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $filename = $request->file($fieldname)->store($path); 
            return asset("/storage/".$directory."/".explode("/",$filename)[2]);
            // if (!is_dir($directory)) {
            //     mkdir($directory, 0777, TRUE);
            // }
            // return  $request->file($fieldname)->store($directory); 

        }

        return null;

    }

    /**
     * Storage on S3
     */
    public function uploadFileS3($uploadedFile,$path,$fileName)
    {
        $storageFile = $uploadedFile->storeAs($path, $fileName, 's3');

        // uncomment if you want to get full url
        // $s3Url  = Storage::disk('s3')->url($storageFile);

        return $storageFile;
    }

    /**
     * Upload content to S3
     */
    public function uploadContentStorageToS3($s3Path,$fileName,$Content)
    {
        $storageFile = Storage::disk('s3')->put($s3Path.'/'.$fileName, $Content);
        return $s3Path.'/'.$fileName;
    }

    /**
     * Upload from local storage to S3
     */
    public function uploadLocalStorageToS3($s3Path,$localPath,$fileName)
    {
        $storageFile = Storage::disk('s3')->putFileAs($s3Path, new File($localPath), $fileName);
        return $storageFile;
    }

    /**
     * Download Private folder file 
     */
    public function downloadFileS3($filename,$time_in_minutes = 60)
    {
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject',[
            'Bucket' => $bucket,
            'Key'    => $filename    // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+'. $time_in_minutes .' minutes');

        // Get the actual presigned-url
        return  (string)$request->getUri();
    }

    /**
     * List all file of specific folder
     */
    public function listFileByDir($dir)
    {
        return Storage::disk('s3')->files($dir);
    }

    /**
     * filename with path
     */
    public function getFileContent($filename)
    {
        $file = Storage::disk('s3')->url($filename);
        return $file;
        //return $this->respondDownload($file, '1608704404_Import 1sr (2).csv','jpg');
    }

    /**
     * Respond with a file download.
     *
     * @param $fileContent
     * @param $fileName
     * @param $mime
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondDownload($fileContent, $fileName, $mime)
    {
        return (new Response($fileContent, 200))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }

    /**
     * Download public directory file
     */
    public function downloadImageFile($filename)
    {
        $headers = array(
            'Content-Type: image/jpeg',
        );
        return Storage::download($filename, $headers);
    }

    /**
     * Download any file from S3
     */
    public function downloadAnyFile($path, $filename){
        try{
            $file_url = $path;
            $file_name  = $filename; 

            $mime = Storage::disk('s3')->getDriver()->getMimetype($file_url);
            $size = Storage::disk('s3')->getDriver()->getSize($file_url);

            $response =  [
                'Content-Type' => $mime,
                'Content-Length' => $size,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$file_name}",
                'Content-Transfer-Encoding' => 'binary',
            ];

            ob_end_clean();

            return Response::make(Storage::disk('s3')->get($file_url), 200, $response);
        }
        catch(Exception $e){
            return $this->respondInternalError( $e->getMessage(), 'object', 500);
        }
    }
}
