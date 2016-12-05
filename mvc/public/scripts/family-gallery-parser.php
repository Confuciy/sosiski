<?php
use Zend\Db\TableGateway\TableGateway;
#use Zend\Db\Sql\Select;
#use Zend\Db\Sql\Sql;

error_reporting(E_ERROR);
ini_set("display_errors", 1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

clearstatcache();

if (file_exists('../../vendor/autoload.php')) {
    $loader = include '../../vendor/autoload.php';
}

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => 'Pdo_Mysql',
    'database' => 'confuciy_sosiski',
    'username' => 'confuciy_sosiski',
    'password' => 'KilyhhhRThT',
    'hostname' => 'db35.valuehost.ru',
    'post'     => '3306',
    'charset'  => 'utf8'
));

function getFolderAsArray($path = ''){
    $folders_and_files = [];
    if($path != '') {
        if(file_exists($path)) {
            $d = dir($path);
            echo "<b>Путь</b>: " . $d->path . "<br>\n";
            while (false !== ($entry = $d->read())) {
                if ($entry != '.' and $entry != '..' and !preg_match('/^preview_/', $entry)) {
                    $folders_and_files[] = $entry;
                }
            }
            $d->close();
        }
    }

    return $folders_and_files;
}

function generate_preview($dir, $name = '', $size, $quality){
    if($name == ''){ return false; }
    if($size === false){ return false; }

    $icfunc = "imagecreatefromjpeg";

    if(!function_exists($icfunc)){ return false; }
    /*
    if($size[0] < $size[1]){
        // если ширина меньше
        $square[0] = floor($size[0] / 2) - floor(200 / 2);
        $square[1] = 100; //floor($size[1] / 2) - floor(200 / 2);
    } else {
        $square[0] = floor($size[0] / 2) - floor(200 / 2);
        $square[1] = floor($size[1] / 2) - floor(200 / 2) - 100;
    }
    */

    $isrc = $icfunc($dir.'/'.$name);

//    v. 1
    if($size[1] > $size[0]){
        $ratio = 288 / $size[1];
        $n_w = $size[0] * $ratio;
        $n_h = 288;
    } elseif($size[0] > $size[1]){
        $ratio = 462 / $size[0];
        $n_w = 462;
        $n_h = $size[1] * $ratio;
    } else {
        $n_w = 462;
        $n_h = 288;
    }

//    v. 2
//    $small_width = 308;
//    $small_height = 192;
//
//    if ($size[0] > $size[1]){$width = $small_width; $height = $small_height;}
//    if ($size[0] < $size[1]){$width = $small_height; $height = $small_width;}
//    if ($size[0] == $size[1]){$width = $small_width; $height = $small_width;}

//    v. 3
//    $width = 160;
//    $height = 100;
//    $width = 308;
//    $height = 192;
//
//    $x_ratio = $width / $size[0];
//    $y_ratio = $height / $size[1];
//    $ratio = max($x_ratio, $y_ratio);
//    $use_x_ratio = ($x_ratio == $ratio);
//    $n_w   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
//    $n_h  = !$use_x_ratio ? $height : floor($size[1] * $ratio);

    echo $n_w.' * '.$n_h.' / ';

//    v. 1
//    $idest = imagecreatetruecolor($n_w, $n_h);
//    imagecopyresized($idest, $isrc, 0, 0, 0, 0, $n_w, $n_h, $size[0], $size[1]);

//    v. 2
//    $idest = imagecreatetruecolor(308, 192);
//    if($n_w == $n_h){
//        imagecopy($idest, $isrc, 0, 0, 0, 0, $size[0], $size[1]);
//    } elseif($n_w > $n_h){
//        imagecopy($idest, $isrc, 0, 0, (($n_w - 308) / 2), 0, $size[0], $size[1]);
//    } else {
//        imagecopy($idest, $isrc, 0, 0, 0, (($n_h - 192) / 2), $size[0], $size[1]);
//    }

//    v. 2
    $idest = imagecreatetruecolor(462, 288);
    if($n_w == $n_h){
        imagecopyresized($idest, $isrc, 0, 0, 0, 0, $n_w, $n_h, $size[0], $size[1]);
    } elseif($n_w > $n_h){
        imagecopyresized($idest, $isrc, 0, 0, (($n_w - 462) / 2), 0, $n_w, $n_h, $size[0], $size[1]);
    } else {
        imagecopyresized($idest, $isrc, 0, 0, ((462 - $n_h) / 2), 0, $n_w, $n_h, $size[0], $size[1]);
    }

    unlink($dir.'/preview_'.$name);
    imagejpeg($idest, $dir.'/preview_'.$name, $quality);
    imagedestroy($isrc);
    imagedestroy($idest);
}

function generate_preview_dirs_resize($dir = ''){
    echo '<b>Preview</b>: '.$dir.'<br />';
    if($dir != ''){
        if($handle = opendir($dir)){
            $col = 1;
            while(false !== ($file = readdir($handle))){
                if($file != "." && $file != ".."){
                    if(is_dir($dir.'/'.$file)){
                        generate_preview_dirs_resize($dir.'/'.$file);
                    } else {
//                        if(!preg_match('|^preview\_|smi', $file)
//                                and (preg_match('|^([А-Яа-яA-Za-z\s\(\)\[\]\_]+)\.[jpg|JPG]$|smi', $file)
//                                    or preg_match('|^IMG\_([0-9]+)\.jpg$|smi', $file)
//                                        or preg_match('|^([0-9]+)\.JPG$|smi', $file))){
//                            if(preg_match('|^IMG\_([0-9]+)\.jpg$|smi', $file)){
//                                //echo $file.' 0= '.($col + 1000).'.jpg<br />';
//                                rename($dir.'/'.$file, $dir.'/'.($col + 1000).'.jpg');
//                                $file = ($col + 1000).'.jpg';
//                                $col++;
//                            } else {
//                                if(preg_match('|^([A-Za-z\d]+)\.JPG$|smi', $file) or preg_match('|^([0-9]+)\.JPG$|smi', $file)){
//                                    //echo $file . ' 1= ' . strtolower($file) . '<br />';
//                                    rename($dir . '/' . $file, $dir . '/' . strtolower($file));
//                                    $file = strtolower($file);
//                                } else {
//                                    //echo $file . ' 2= ' . $col . '.jpg<br />';
//                                    rename($dir . '/' . $file, $dir . '/' . $col . '.jpg');
//                                    $file = $col . '.jpg';
//                                    $col++;
//                                }
//                            }
//                        }

                        if(preg_match('|^preview\_|smi', $file)){
//                            unlink($dir.'/'.$file);
                        }
                        if(!preg_match('|^preview\_|smi', $file)) {
                            $size = getimagesize($dir . '/' . $file);
                            generate_preview($dir, $file, $size, 100);
                            echo '+ preview_' . $file . '<br />' . "\r\n";
                        }
//                        if(!preg_match('|^preview\_|smi', $file)){
//                            if(is_file($dir.'/preview_'.$file)){
//                                unlink($dir.'/preview_'.$file);
//                                echo 'unlink ';
//                                $size = getimagesize($dir.'/'.$file);
//                                generate_preview($dir, $file, $size, 100);
//                                echo '|_ preview_'.$file.'<br />'."\r\n";
//                            } else {
//                                $size = getimagesize($dir.'/'.$file);
//                                generate_preview($dir, $file, $size, 100);
//                                echo '+ preview_'.$file.'<br />'."\r\n";
//                            }
//                        }
                    }

//                    if(is_dir($dir.'/'.$file)){
//                        $dir_arr = scandir($dir.'/'.$file);
//                        $nums = 0;
//                        foreach($dir_arr as $row){
//                            if($row != '.' and $row != '..' and $row != 'nums.txt' and !preg_match('/preview_/', $row)){
//                                $nums++;
//                            }
//                        }
//
//                        $filename = $dir.'/'.$file.'/nums.txt';
//                        if(!$n_handle = fopen($filename, 'w')){
//                            echo "Не могу открыть файл ($filename)<br />";
//                            exit;
//                        }
//                        if (fwrite($n_handle, $nums) === FALSE){
//                            echo "Не могу произвести запись в файл ($filename)<br />";
//                            exit;
//                        } else {
//                            echo 'nums: '.$nums.'<br />';
//                        }
//                        fclose($n_handle);
//                    }
                }
            }
            closedir($handle);
        }
    }
    return;
}

$galleryMemberTable = new TableGateway('family_gallery_member', $adapter);
$galleryTable = new TableGateway('family_gallery', $adapter);
$galleryPhotosTable = new TableGateway('family_gallery_photo', $adapter);

$rowset = $galleryMemberTable->select();
foreach ($rowset as $row) {

    echo 'id: '.$row->id.', name: '.$row->name.', info: '.$row->info.', date: '.$row->date.
        ', photo: '.$row->photo.', state: '.$row->state.'<br />';

    $years = getFolderAsArray("../img/family-gallery-member/" . $row->id . '/photos');
    if(sizeof($years)){
        //echo '<pre>'; print_r($years); echo '</pre>';
        foreach ($years as $year) {
            echo '<b>Год</b>: '.$year.'<br />'."\n";
            $months = getFolderAsArray("../img/family-gallery-member/" . $row->id . '/photos/' . $year);
            if(sizeof($months)){
                echo '<pre>'; print_r($months); echo '</pre>';
                foreach ($months as $month){
                    $id = 0;
                    $rowset = $galleryTable->select(array('member_id' => $row->id, 'year' => $year, 'month' => $month));
                    $galleryRow = $rowset->current();
                    if(isset($galleryRow['id']) and !empty($galleryRow['id'])){
                        $data = array(
                            'title'     => $year . ' / ' . $month,
                            'text'      => $row->name . ' / ' . $year . ' / ' . $month
                        );
                        $galleryTable->update($data, array('id' => $galleryRow['id']));
                        $id = $galleryRow['id'];
                    } else {
                        $data = array(
                            'title'     => $year . ' / ' . $month,
                            'text'      => $row->name . ' / ' . $year . ' / ' . $month,
                            'year'      => $year,
                            'month'     => $month,
                            'state'     => 1,
                            'member_id' => $row->id
                        );
                        $galleryTable->insert($data);
                        $id = $galleryTable->getLastInsertValue();
                    }

                    echo '<b>Месяц</b>: '.$month.'<br />'."\n";
                    $photos = getFolderAsArray("../img/family-gallery-member/" . $row->id . '/photos/' . $year . '/' . $month);
                    if(sizeof($photos)){
                        #if($year == 2014 and (int)$month == 4){
                            generate_preview_dirs_resize('/pub/home/confuciy/htdocs_sosiski/mvc/public/img/family-gallery-member/' . $row->id . '/photos/' . $year . '/' . $month);
                        #}
//                        echo '<pre>'; print_r($photos); echo '</pre>';
                        $data = array(
                            'state' => 1
                        );
                        $galleryTable->update($data, array('id' => $id));

                        $col = 0;
                        foreach($photos as $photo) {
                            $rowset = $galleryPhotosTable->select(array('gallery_id' => $id, 'path' => $photo));
                            $galleryPhotoRow = $rowset->current();
                            if(!isset($galleryPhotoRow['id'])) {
                                $data = array(
                                    'name' => $photo,
                                    'info' => $row->name . ' / ' . $year . ' / ' . $month . ' / ' . $photo,
                                    'date' => $year . '-' . $month . '-01',
                                    'state' => 1,
                                    'gallery_id' => $id,
                                    'path' => $photo,
                                    'sortby' => $col
                                );
                                $galleryPhotosTable->insert($data);
                            }
                            $col++;
                        }
                    } else {
                        $data = array(
                            'state' => 0
                        );
                        $galleryTable->update($data, array('id' => $id));
                    }
                }
            }
        }
    }
}

#echo 'id: '.$row->id.', title: '.$row->title.', text: '.$row->text.', member_id: '.$row->member_id.
#    ', year: '.$row->year.', month: '.$row->month.', state: '.$row->state.'<br />';

/*
$rowset = $galleryTable->select(function (Select $select) {
    $select->order('title ASC')->limit(2);
});

foreach ($rowset as $row){
    echo '<pre>'; print_r($row); echo '</pre>';
}

$data = array(
    'title'=> '111'
);
$galleryTable->insert($data);

echo '<pre>'; print_r($otherTable); echo '</pre>';

echo '<br /><hr /><br />';
*/