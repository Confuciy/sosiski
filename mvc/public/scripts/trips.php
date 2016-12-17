<?php
//header("cache-control: max-age=864000, must-revalidate");
#header("Expires: ".date('r', mktime(0, 0, 0, date("m"), date("d") + 10, date("Y"))));
#echo '<pre>'; print_r($_GET); echo '</pre>';
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trips</title>
    <style>
        body { font-family: tahoma; font-size: 14px; }
        section { padding: 0; }

        div.clr { clear: both; }
        div.clr-hr { clear: both; padding-top: 10px; }
        hr { border: 0; border-bottom: 1px solid #e5e5e5; }

        div.ym { clear: both; width: 100%; text-align: center; }

        ul { list-style-type: none; }

        ul#ul-im li div:hover { border: 1px solid white; opacity: 0.3; }

        ul#ul-sections li { float: left; margin-right: 10px; padding: 0 2px; }
        ul#ul-sections li a { font-weight: bold; text-decoration: none; }
        ul#ul-sections li a:hover { background: #f5f5f5; }
        ul#ul-sections li a#active { background: #e5e5e5; padding: 0 5px; }

        ul#ul-main li div { padding: 0 2px; }
        ul#ul-main li span { padding: 0 2px; }
        ul#ul-main li div:hover { background: #f5f5f5; }
        ul#ul-main li div#active { background: #e5e5e5; }
        ul#ul-main li span#active { background: #e5e5e5; }
        ul#ul-main li span#year { font-size: 16px; font-weight: bold; }
        ul#ul-main li span#active-year { background: #e5e5e5; font-size: 16px; font-weight: bold; }
        ul#ul-main li.under-main { clear:both; font-weight: bold; }
        ul#ul-main li div.main, span.main { float: left; margin: 5px 5px 5px 0; cursor:pointer; }
        ul#ul-main li div.main-group, span.main-group { float: left; margin: 5px 0px 5px 0; padding-right: 5px; cursor:pointer; background: #f5f5f5; }

        ul#ul-pages { margin-top: -10px; }
        ul#ul-pages li { float: left; width: 40px; padding: 5px 0; margin-right: 5px; margin-top: 5px; border: 1px solid gray; border-radius: 5px; text-align: center; }
        ul#ul-pages li#active { background: #e5e5e5; }
        ul#ul-pages a li { font-weight: bold; text-decoration: none; }

        ul li:first-child div { float: none; }
        ul li:first-child div a { font-weight: bold; }
        ul li.prev a { text-decoration: none; }
        ul li.prev { width: 200px; height: 200px; border: 1px solid black; border-radius: 5px; float: left; margin: 0 5px 5px 0; cursor:pointer; }
        ul li.prev:hover { border: 1px solid white; opacity: 0.3; }
        ul li.prev span.del {  border: 2px solid white; color: white; font-weight: bold; left: 165px; padding: 5px 9px; position: relative; top: 10px; z-index: 1; }
    </style>
</head>
<body>
<header>
    <nav>
        <?php
        $months_name_arr = array(
            '1' => 'январь', '01' => 'январь',
            '2' => 'февраль', '02' => 'февраль',
            '3' => 'март', '03' => 'март',
            '4' => 'апрель', '04' => 'апрель',
            '5' => 'май', '05' => 'май',
            '6' => 'июнь', '06' => 'июнь',
            '7' => 'июль', '07' => 'июль',
            '8' => 'август', '08' => 'август',
            '9' => 'сентябрь', '09' => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь',
        );

        function clearFolder($folder = ''){
            return (preg_match('/\|/smi', $folder)?mb_substr($folder, (mb_strpos($folder, '|', 0, 'UTF-8') + 1), mb_strlen($folder, 'UTF-8'), 'UTF-8'):$folder);
        }

        function setCacheArr($md_cache, $filename, $arr){
            $filename = $_SERVER['DOCUMENT_ROOT'].'/sos/trips_cache/'.$md_cache.'_'.$filename.'.txt';
            $content = serialize($arr);

            if(!$handle = fopen($filename, 'w')){
                echo "Не могу открыть файл ($md_cache_$filename.txt)";
                exit;
            }
            if(fwrite($handle, $content) === FALSE){
                echo "Не могу произвести запись в файл ($md_cache_$filename.txt)";
                exit;
            }
            fclose($handle);
        }

        function clearStr($str = ''){
            return str_replace(array('_'), array(' '), $str);
        }

        function getCacheArr($md_cache, $filename){
            $filename = $_SERVER['DOCUMENT_ROOT'].'/sos/trips_cache/'.$md_cache.'_'.$filename.'.txt';
            $handle = fopen($filename, "r");
            $content = fread($handle, filesize($filename));
            fclose($handle);

            return ($content != ''?unserialize($content):array());
        }

        $cache_arr = 1;
        $md_cache = md5(implode('|', $_GET));

        #echo '<pre>'; print_r($_GET); echo '</pre>';

        $limit_on_page = 100;
        $page = ((isset($_GET['page']) and !empty($_GET['page']))?$_GET['page']:1);

        echo '<ul id="ul-sections">'."\r\n";
        echo '<li><a href="/"'.(preg_match('|^\/$|smi', '/'.trim($_SERVER['REQUEST_URI'], '/'))?' id="active"':'').'>Main</a></li>'."\r\n";
        #echo '<li><a href="/instagram/"'.(preg_match('|^instagram|smi', trim($_SERVER['REQUEST_URI'], '/'))?' id="active"':'').'>Instagram</a></li>'."\r\n";
        echo '<li><a href="/trips/"'.(preg_match('|^trips|smi', trim($_SERVER['REQUEST_URI'], '/'))?' id="active"':'').'>Trips</a></li>'."\r\n";
        echo '</ul>'."\r\n";
        echo '<div class="clr-hr"><hr /></div>'."\r\n";

        function get_main_dirs($dir = '', $main_dir_arr = array()){
            if($dir != ''){
                if($handle = opendir($dir.'/')){
                    while(false !== ($file = readdir($handle))){
                        if($file != "." && $file != ".."){
                            if(is_dir($dir.'/'.$file)){
                                $main_dir_arr[$file] = get_main_dirs($dir.'/'.$file, $main_dir_arr[$file]);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
            return $main_dir_arr;
        }

        if($cache_arr == 1){
            $main_dir_arr = getCacheArr($md_cache, 'main_dir_arr');
        }
        if(empty($cache_arr) or !sizeof($main_dir_arr)){
            $main_dir_arr = get_main_dirs($_SERVER['DOCUMENT_ROOT'].'/sos/trips');
            setCacheArr($md_cache, 'main_dir_arr', $main_dir_arr);
        }
        #echo '<pre>'; print_r($main_dir_arr); echo '</pre>';
        if(sizeof($main_dir_arr) > 0){
            krsort($main_dir_arr);
            foreach($main_dir_arr as $year => $trips){
                $under_trip = array();
                echo '<ul id="ul-main">'."\r\n";
                echo '
						<li>
							<span class="main"'.((isset($_GET['year']) and $_GET['year'] == $year)?' id="active-year"':'id="year"').'>'.$year.'</span>
						</li>'."\r\n";
                ksort($trips);
                foreach($trips as $trip => $items){
                    $trip = clearFolder($trip);
                    if(sizeof($items) > 0){
                        $under_trip[] = '
								<li class="under-main">
									<span class="main-group"'.((isset($_GET['trip']) and $_GET['trip'] == $trip)?' id="active"':'').'>'.clearStr($trip).':</span>
								</li>'."\r\n";
                        ksort($items);
                        foreach($items as $item => $val){
                            $item = clearFolder($item);
                            $under_trip[] = '
									<li>
										<div class="main-group"'.((isset($_GET['year']) and isset($_GET['trip']) and isset($_GET['under_trip']) and $_GET['year'] == $year and $_GET['trip'] == $trip and $_GET['under_trip'] == $item)?' id="active"':'').'><a href="/trips/'.$year.'/'.$trip.'/'.$item.'">'.clearStr($item).'</a></div>
									</li>'."\r\n";
                        }
                    } else {
                        echo '
								<li>
									<div class="main"'.((isset($_GET['year']) and isset($_GET['trip']) and $_GET['year'] == $year and $_GET['trip'] == $trip)?' id="active"':'').'><a href="/trips/'.$year.'/'.$trip.'">'.clearStr($trip).'</a></div>
								</li>'."\r\n";
                    }
                }
                echo implode('', $under_trip);
                echo '</ul>'."\r\n";
                echo '<div class="clr"></div>'."\r\n";
            }
        }
        ?>
    </nav>
</header>
<section>
    <?php
    if(isset($_GET['year']) or isset($_GET['trip']) or isset($_GET['under_trip'])){
        function get_preview_dirs($dir = '', $dir_arr = array(), $md = 0){
            if($dir != ''){
                if($handle = opendir($dir.'/')){
                    while(false !== ($file = readdir($handle))){
                        if($file != "." && $file != ".."){
                            if(empty($md) and isset($_GET['year'])){
                                #echo '[year] '.$dir.'/'.$file.'<br />';
                                if(is_dir($dir.'/'.$file) and $file == $_GET['year']){
                                    $dir_arr[$file] = get_preview_dirs($dir.'/'.$file, $dir_arr[$file], 1);
                                } else {
                                    if(preg_match('|^preview\_|smi', $file)){
                                        $dir_arr[] = $file;
                                    }
                                }
                            } elseif($md == 1 and isset($_GET['trip'])){
                                #echo '[trip] '.$dir.'/'.$file.'<br />';
                                if(is_dir($dir.'/'.$file) and clearFolder($file) == $_GET['trip']){
                                    $dir_arr[$file] = get_preview_dirs($dir.'/'.$file, $dir_arr[$file], 2);
                                } else {
                                    if(preg_match('|^preview\_|smi', $file)){
                                        $dir_arr[] = $file;
                                    }
                                }
                            } elseif(isset($_GET['under_trip'])){
                                #echo '[under_trip] '.$dir.'/'.$file.'<br />';
                                if(is_dir($dir.'/'.$file) and clearFolder($file) == $_GET['under_trip']){
                                    $dir_arr[$file] = get_preview_dirs($dir.'/'.$file, $dir_arr[$file], $md);
                                } else {
                                    if(preg_match('|^preview\_|smi', $file)){
                                        $dir_arr[] = $file;
                                    }
                                }
                            } else {
                                #echo '[images] '.$dir.'/'.$file.'<br />';
                                if(is_dir($dir.'/'.$file)){
                                    $dir_arr[$file] = get_preview_dirs($dir.'/'.$file, $dir_arr[$file], $md);
                                } else {
                                    if(preg_match('|^preview\_|smi', $file)){
                                        $dir_arr[] = $file;
                                    }
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            }
            return $dir_arr;
        }

        if($cache_arr == 1){
            $dir_arr = getCacheArr($md_cache, 'dir_arr');
        }
        if(empty($cache_arr) or !sizeof($dir_arr)){
            $dir_arr = get_preview_dirs($_SERVER['DOCUMENT_ROOT'].'/sos/trips');
            setCacheArr($md_cache, 'dir_arr', $dir_arr);
        }
        if(sizeof($dir_arr) > 0){
            #echo '<pre>'; print_r($_GET); echo '</pre>';
            #echo '<pre>'; print_r($dir_arr); echo '</pre>';

            $pages = 0;
            $col = 0;
            foreach($dir_arr as $year => $trips){
                ksort($trips);
                foreach($trips as $trip => $images){
                    #echo '<pre>'; print_r($images); echo '</pre>';

                    $keys = array_keys($images);
                    if(empty($keys[0])){
                        sort($images);

                        if(ceil(sizeof($images) / $limit_on_page) > 1){
                            $images = array_chunk($images, $limit_on_page);
                            $pages = sizeof($images);
                            $images = $images[$page - 1];
                        }

                        echo '<div class="ym" id="'.$year.'_'.$trip.'"><h2>'.$year.' / '.clearStr(clearFolder($trip)).'</h2></div>';
                        if($pages > 0){
                            echo '<ul id="ul-pages">'."\r\n";
                            for($i = 1; $i <= $pages; $i++){
                                echo '
										<a href="/trips/'.$year.'/'.clearFolder($trip).'/p'.$i.'">
											<li'.($i == $page?' id="active"':'').'>
												'.$i.'
											</li>
										</a>'."\r\n";
                            }
                            echo '</ul>'."\r\n";
                            echo '<div class="clr"></div>'."\r\n";
                        }
                        echo '<ul id="ul-im">'."\r\n";
                        foreach($images as $image){
                            $src = '/sos/trips/'.$year.'/'.$trip.'/'.str_replace('preview_', '', $image);
                            $image_info = getimagesize($_SERVER['DOCUMENT_ROOT'].$src);
                            echo '
									<a href="'.$src.'" data-lightbox="'.$year.'_'.$trip.'">
										<li class="prev" id="li'.$col.'" style="background-image: url(/sos/trips/'.$year.'/'.$trip.'/'.$image.');">
											&nbsp;
										</li>
									</a>'."\r\n";
                            $col++;
                        }
                        echo '</ul>'."\r\n";
                    } else {
                        sort($images[$keys[0]]);
                        if(ceil(sizeof($images[$keys[0]]) / $limit_on_page) > 1){
                            $images = array_chunk($images[$keys[0]], $limit_on_page);
                            $pages = sizeof($images);
                            $images = $images[($page - 1)];
                        } else {
                            $images = $images[$keys[0]];
                        }

                        echo '<div class="ym" id="'.$year.'_'.$trip.'"><h2>'.$year.' / '.clearFolder($trip).' / '.clearFolder($keys[0]).'</h2></div>';
                        if($pages > 0){
                            echo '<ul id="ul-pages">'."\r\n";
                            for($i = 1; $i <= $pages; $i++){
                                echo '
										<a href="/trips/'.$year.'/'.clearFolder($trip).'/'.clearFolder($keys[0]).'/p'.$i.'">
											<li'.($i == $page?' id="active"':'').'>
												'.$i.'
											</li>
										</a>'."\r\n";
                            }
                            echo '</ul>'."\r\n";
                            echo '<div class="clr"></div>'."\r\n";
                        }
                        echo '<ul id="ul-im">'."\r\n";
                        foreach($images as $image){
                            $src = '/sos/trips/'.$year.'/'.$trip.'/'.$keys[0].'/'.str_replace('preview_', '', $image);
                            $image_info = getimagesize($_SERVER['DOCUMENT_ROOT'].$src);
                            echo '
									<a href="'.$src.'" data-lightbox="'.$year.'_'.$trip.'">
										<li class="prev" id="li'.$col.'" style="background-image: url(/sos/trips/'.$year.'/'.$trip.'/'.$keys[0].'/'.$image.'");">
											&nbsp;
										</li>
									</a>'."\r\n";
                            $col++;
                        }
                        echo '</ul>'."\r\n";
                    }
                    echo '<div class="clr-hr"><hr /></div>'."\r\n";
                }
            }
        }
    }
    ?>
</section>
<script src="/sos/js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="/sos/js/lightbox/js/lightbox.min.js" type="text/javascript"></script>
<link href="/sos/js/lightbox/css/lightbox.css" rel="stylesheet">
<?php /* ?>
	<script>
	function delImage(image_id, image_path, image_path_preview){
		if(confirm("Вы действительно желаете удалить изображение?")){
			$.ajax({
				type: "POST",
				url: "/scripts/delete_image.php",
				dataType : "html",
				data: ({ image_id: image_id, image_path: image_path, image_path_preview: image_path_preview }),
				async: false,
				success: function (data, textStatus){
					$("#li" + image_id).hide("slow");
				}
			});
		}
	}
	</script>
	<?php */ ?>
</body>
</html>