<?php
$url = 'https://api.instagram.com/oauth/authorize/?client_id=b29e26b3d4f34883a06aa475a629b08e&redirect_uri=http://sosiski.net/scripts/instagram.php&response_type=code&scope=basic';
$main_path = '/pub/home/confuciy/htdocs_sosiski/mvc';
$files_path = $main_path.'/public/scripts/instagram';
$code_file = $files_path.'/instagram_code.txt';
$token_file = $files_path.'/instagram_token.txt';

if (file_exists($main_path.'/vendor/autoload.php')) {
    $loader = include $main_path.'/vendor/autoload.php';
}

if (file_exists($main_path.'/config/autoload/global.php')) {
    $global = include $main_path.'/config/autoload/global.php';
}
if (file_exists($main_path.'/config/autoload/local.php')) {
    $local = include $main_path.'/config/autoload/local.php';
}

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => $global['db']['driver'],
    'dsn' => $global['db']['dsn'],
    'username' => $local['db']['username'],
    'password' => $local['db']['password'],
    'driver_options' => $global['db']['driver_options'],
));

if(isset($_GET['code'])){
    $handle = fopen($code_file, 'w+');
    fwrite($handle, $_GET['code']);
    fclose($handle);

    $data = array(
        'client_id' => 'b29e26b3d4f34883a06aa475a629b08e',
        'client_secret' => '5e7d14ee62674baf9919a38bba4d158c',
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'http://sosiski.net/scripts/instagram/instagram.php',
        'code' => $_GET['code']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $html = curl_exec($ch);
    curl_close($ch);

    $req_data = json_decode($html, 1);

    $handle = fopen($token_file, 'w+');
    fwrite($handle, $req_data['access_token']);
    fclose($handle);
} else {
    $handle = fopen($token_file, "r");
    $token = fread($handle, filesize($token_file));
    fclose($handle);

    if($token != ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/v1/users/self/media/recent/?access_token='.$token.'&count=5');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $html = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($html, 1);

        if(isset($data['data']) and sizeof($data['data']) > 0){

            $res = new Zend\Db\TableGateway\TableGateway('lang', $adapter);
            $sql = $res->getSql();
            $select = $sql->select();
            $langs = $res->selectWith($select)->toArray();

            if(sizeof($langs) > 0){
                foreach($langs as $lang){
                    setlocale(LC_ALL, $lang['locale'].".UTF-8");

$html = '
<section>
    <ul class="posts">';
                    foreach($data['data'] as $row){
                        $title = '';
                        if($row['location']['name'] != ''){
                            $title = $row['location']['name'];
                        } else {
                            if($row['caption']['text'] != '') {
//                                $text_arr = explode(' ', $row['caption']['text']);
//                                $title = implode(' ', $text_arr);
                                $title = mb_substr($row['caption']['text'], 0, 40, 'UTF-8').'..';
                            }
                        }
                        $html .= '
                <li>
                    <article>
                        <header style="margin-top: -5px;">
                            <span class="icon fa-heart" style="-moz-osx-font-smoothing: grayscale; -webkit-font-smoothing: antialiased; font-size: 0.8em !important; letter-spacing: 0.25em !important;">'.$row['likes']['count'].'</span>
                            <h3><a href="'.$row['link'].'" target="_blank">'.$title.'</a></h3>
                            <time class="published" datetime="'.date('Y-m-d', $row['created_time']).'">'.getNormalMouth(strftime('%B %e, %Y', $row['created_time']), $lang['locale']).'</time>
                        </header>
                        <a href="'.$row['link'].'" class="image" target="_blank"><img src="'.$row['images']['thumbnail']['url'].'" alt="" /></a>
                    </article>
                </li>';
                    }
                    $html .= '</ul>
</section>';

                    $handle = fopen($files_path.'/instagram_section_'.$lang['locale'].'.txt', 'w+');
                    fwrite($handle, $html);
                    fclose($handle);
                }
            }
        }
    }
}

function getNormalMouth($str = '', $locale = '')
{
    if($locale != 'ru_RU'){
        return $str;
    }

    $search_months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    $replace_months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

    return str_replace($search_months, $replace_months, $str);
}

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/oauth/authorize/?client_id=b29e26b3d4f34883a06aa475a629b08e&redirect_uri=http://sosiski.net/scripts/instagram.php&response_type=code&scope=basic');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
//curl_setopt($ch, CURLOPT_POST, 1);
////curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//$html = curl_exec($ch);
//curl_close($ch);