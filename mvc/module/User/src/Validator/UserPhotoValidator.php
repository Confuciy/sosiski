<?php
namespace User\Validator;

use Zend\Validator\AbstractValidator;
//use User\Validator\FileValidatorInterface;
use Zend\Validator\File\Extension;
//use Zend\File\Transfer\Adapter\Http;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\UploadFile;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\FilesSize;
//use Zend\Filter\File\Rename;
//use Zend\Validator\File\MimeType;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;

class UserPhotoValidator extends AbstractValidator
{
    const FILE_EXTENSION_ERROR  = 'invalidFileExtention';
    const FILE_NAME_ERROR       = 'invalidFileName';
    const FILE_INVALID          = 'invalidFile';
    const FALSE_EXTENSION       = 'fileExtensionFalse';
    const NOT_FOUND             = 'fileExtensionNotFound';
    const TOO_BIG               = 'fileFilesSizeTooBig';
    const TOO_SMALL             = 'fileFilesSizeTooSmall';
    const NOT_READABLE          = 'fileFilesSizeNotReadable';
    const IS_EMPTY              = 'isEmpty';

    public $minSize = 64;  //KB
    public $maxSize = 1024; //KB
    public $overwrite = true;
    public $newFileName = null;
    public $uploadPath = './img/users/';
    public $extensions = array('jpg', 'png', 'gif', 'jpeg');
    public $mimeTypes = array(
        'image/gif',
        'image/jpg',
        'image/png',
    );

    protected $messageTemplates = array(
        self::FILE_EXTENSION_ERROR  => "File extension is not correct",
        self::FILE_NAME_ERROR       => "File name is not correct",
        self::FILE_INVALID          => "File is not valid",
        self::FALSE_EXTENSION       => "File has an incorrect extension",
        self::NOT_FOUND             => "File is not readable or does not exist",
        self::TOO_BIG               => "All files in sum should have a maximum size of '%max%' but '%size%' were detected",
        self::TOO_SMALL             => "All files in sum should have a minimum size of '%min%' but '%size%' were detected",
        self::NOT_READABLE          => "One or more files can not be read",
        self::IS_EMPTY              => "Is empty",
    );

    protected $fileAdapter;

    protected $validators;

    protected $filters;

    public function __construct($options)
    {
        $this->fileAdapter = new FileInput('photo');
        parent::__construct($options);
    }

    public function isValid($fileInput)
    {
        $options = $this->getOptions();
        $extensions = $this->extensions;
        $minSize    = $this->minSize;
        $maxSize    = $this->maxSize;
        $newFileName = $this->newFileName;
        $uploadPath = $this->uploadPath;
        $overwrite = $this->overwrite;
        if (array_key_exists('extensions', $options)) {
            $extensions = $options['extensions'];
        }
        if (array_key_exists('minSize', $options)) {
            $minSize = $options['minSize'];
        }
        if (array_key_exists('maxSize', $options)) {
            $maxSize = $options['maxSize'];
        }
        if (array_key_exists('newFileName', $options)) {
            $newFileName = $options['newFileName'];
        }
        if (array_key_exists('uploadPath', $options)) {
            $uploadPath = $options['uploadPath'];
        }
        if (array_key_exists('overwrite', $options)) {
            $overwrite = $options['overwrite'];
        }
        $fileName   = $fileInput['name'];
        $fileSizeOptions = null;
        if ($minSize) {
            $fileSizeOptions['min'] = $minSize*1024 ;
        }
        if ($maxSize) {
            $fileSizeOptions['max'] = $maxSize*1024 ;
        }
        if ($fileSizeOptions) {
            $this->validators[] = new FilesSize($fileSizeOptions);
        }
        $this->validators[] = new Extension(array('extension' => $extensions));
        if (! preg_match('/^[a-z0-9-_]+[a-z0-9-_\.]+$/i', $fileName)) {
            $this->error(self::FILE_NAME_ERROR);
            return false;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (! in_array($extension, $extensions)) {
            $this->error(self::FILE_EXTENSION_ERROR);
            return false;
        }
        if ($newFileName) {
            $destination = $newFileName.".$extension";
            if (! preg_match('/^[a-z0-9-_]+[a-z0-9-_\.]+$/i', $destination)) {
                $this->error(self::FILE_NAME_ERROR);
                return false;
            }
        } else {
            $destination = $fileName;
        }


//        if(!file_exists($options['uploadPath'])){
//            mkdir($options['uploadPath'], 0755);
//        }
//        if(move_uploaded_file($fileInput['tmp_name'], $uploadPath.$destination)){
//            $res = new \Zend\Db\TableGateway\TableGateway('user', $options['dbAdapter']);
//            $sql = $res->getSql();
//            $update = $sql->update();
//            $update->table('user');
//            $update->set(['photo' => $destination]);
//            $update->where(array('id' => $options['id']));
//            $statement = $sql->prepareStatementForSqlObject($update);
//            $statement->execute($sql);
//
//            return true;
//        } else {
//            return false;
//        }


//        $file = new FileInput('file');
//        $file->setRequired(false);
////        $file->getValidatorChain()->attach(new UploadFile);
//        $file->getFilterChain()->attach(new RenameUpload(array(
//            'target'                => $uploadPath.$destination,
//            'overwrite'             => true,
//            'use_upload_extension'  => true,
//            'use_upload_name'       => true,
//            'randomize'             => true,
//        )));
//
//
//
//        $inputFilter = new InputFilter($file);
//        $inputFilter->add($file);
//        //echo '<pre>'; print_r($inputFilter->getValues()); echo '</pre>';
//        $r = $file->getValue();
//        echo '<pre>'; print_r($r); echo '</pre>';
//        die('fds');
//
//
////        echo '<pre>'; print_r($fileInput); echo '</pre>';
////        die;
//
//        if ($file->isValid()) {
//            return true;
//        } else {
//            $messages = $file->getMessages();
//            if ($messages) {
//                $this->setMessages($messages);
//                foreach ($messages as $key => $value) {
//                    $this->error($key);
//                }
//            } else {
//                $this->error(self::FILE_INVALID);
//            }
//            return false;
//        }
//
        $renameOptions['target'] = $uploadPath.$destination;
        $renameOptions['overwrite'] = $overwrite;
        $this->fileAdapter->setRequired(false);
        $this->fileAdapter->getFilterChain()->attach(new RenameUpload($renameOptions));
        $this->fileAdapter->getValidatorChain()->attach(new UploadFile($this->validators));
        if ($this->fileAdapter->isValid()) {
            if(!file_exists($options['uploadPath'])){
                mkdir($options['uploadPath'], 0755);
            }

            // remove old photos
            $di = new RecursiveDirectoryIterator($options['uploadPath'], FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ( $ri as $file ) {
                $file->isDir() ?  rmdir($file) : unlink($file);
            }

            if(move_uploaded_file($fileInput['tmp_name'], $uploadPath.$destination)){
                $size = getimagesize($uploadPath.$destination);
                $isrc = imagecreatefromjpeg($uploadPath.$destination);
                $idest = imagecreatetruecolor(36, 36);
                imagecopyresized($idest, $isrc, 0, 0, 0, 0, 36, 36, $size[0], $size[1]);
                imagejpeg($idest, $uploadPath.'small_'.$destination);
                imagedestroy($isrc);
                imagedestroy($idest);

                $res = new \Zend\Db\TableGateway\TableGateway('user', $options['dbAdapter']);
                $sql = $res->getSql();
                $update = $sql->update();
                $update->table('user');
                $update->set(['photo' => $destination]);
                $update->where(array('id' => $options['id']));
                $statement = $sql->prepareStatementForSqlObject($update);
                $statement->execute($sql);

                return true;
            } else {
                return false;
            }
        } else {
            $messages = $this->fileAdapter->getMessages();
            if ($messages) {
                $this->setMessages($messages);
                foreach ($messages as $key => $value) {
                    $this->error($key);
                }
            } else {
                $this->error(self::FILE_INVALID);
            }
            return false;
        }
    }

}