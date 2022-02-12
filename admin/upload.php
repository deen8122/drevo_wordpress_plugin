<?php
// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($string) {

    // From http://w3.org/International/questions/qa-forms-utf-8.html
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);

} // function is_utf8
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $disallowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(),array $disallowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
        $disallowedExtensions = array_map("strtolower", $disallowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        if(!in_array('php',$disallowedExtensions))$disallowedExtensions[] = 'php';
        $this->disallowedExtensions = $disallowedExtensions;
        $this->sizeLimit = $sizeLimit;

        //$this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

		$file1 = $this->file->getName();
        $pathinfo = pathinfo($file1);
        $ext = $pathinfo['extension'];
        if(is_utf8($file1)) $filename = filename(translit_utf(substr($file1,0,-strlen($ext)-1)));
        else $filename = filename(translit(substr($file1,0,-strlen($ext)-1)));
		$filename = substr($filename,0,55);
        //$filename = md5(uniqid());

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        if($this->disallowedExtensions && in_array(strtolower($ext), $this->disallowedExtensions)){
            $these = implode(', ', $this->disallowedExtensions);
            return array('error' => 'File has an invalid extension, it should not be one of '. $these . '.');
        }

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            $i = 0;
            while (file_exists($uploadDirectory . $filename . (empty($i)?"":"_".$i) . '.' . $ext)) {
                $i++;
            }
            $filename .= (empty($i)?"":"_".$i);
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>$filename . '.' . $ext);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }

    }
}

$allowedExtensions = array();
$disallowedExtensions = array();
// max file size in bytes
$sizeLimit = 30 * 1024 * 1024;
$folder = DATA_FLD.'/good_photo/';
if(!empty($_GET['aExt'])) $allowedExtensions = explode(",",$_GET['aExt']);
if(!empty($_GET['dExt'])) $disallowedExtensions = explode(",",$_GET['dExt']);
if(isset($_GET['size'])) $sizeLimit = (int)$_GET['size'];
if(isset($_GET['folder'])) $folder = $_GET['folder'];
$disallowedExtensions[]='php';
$uploader = new qqFileUploader($allowedExtensions, $disallowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($folder);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
die();
