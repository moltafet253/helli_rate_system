<?php
ini_set('display_errors', 1);
include_once __DIR__ . '/../../config/connection.php';
session_start();
$user = $_SESSION['username'];
$select = mysqli_query($connection, "select * from log_helli where username='$user' order by radif desc limit 1");
foreach ($select as $markforlinklogs) {
}
$LinkLogID = @$markforlinklogs['radif'];
$dateforupdateloghelli = $year . '/' . $month . '/' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
$urlofthispage = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

if (isset($_POST['querya'])) {
    $a = 1;
//    $query=mysqli_query($connection,"select * from etelaat_a inner join tafsili3_ostan on etelaat_a.codeasar=tafsili3_ostan.codeasar");
//    foreach ($query as $item){
//        $codeasar=$item['codeasar'];
//        $rater_id=$item['rater_id'];
//        mysqli_query($connection,"update etelaat_a set codearzyabtafsili3_ostani='$rater_id' where codeasar='$codeasar' ");
//    }
//    $query = mysqli_query($connection, "select * from etelaat_a inner join tafsili1_ostan on etelaat_a.codeasar=tafsili1_ostan.codeasar where tafsili1_ostan.jam<70");
//    foreach ($query as $items) {
//        echo $a . '-' . $codeasar = $items['codeasar'];
//        $query = "update etelaat_a set nobat_arzyabi_ostani='تفصیلی اول', vaziatkarnameostani='اتمام ارزیابی', bargozideh_ostani=NULL where codeasar='$codeasar'";
//        if (mysqli_query($connection, $query)) {
//            echo 'done' . '<br>';
//        }
//        $a++;
//    }
//    $query = mysqli_query($connection, "select * from etelaat_a inner join tafsili1_madrese on etelaat_a.codeasar=tafsili1_madrese.codeasar where tafsili1_madrese.jam<70");
//    foreach ($query as $items) {
//        echo $a . '-' . $items['codeasar'] . '<br>';
//        $query = "update etelaat_a set nobat_arzyabi_madrese='تفصیلی اول', vaziatkarnamemadrese='اتمام ارزیابی' where codeasar='$codeasar'";
//        if (mysqli_query($connection, $query)) {
//            echo 'done' . '<br>';
//        }
//        $a++;
//    }
} //inc for attach file admin to asar
elseif (isset($_POST['attachfileadmin']) and !empty($_POST['codeasar']) and !empty($_POST['adhesive']) and !empty($_FILES['fileasar'])) {
    $operation = "attachfileadmin";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $file_size = $_FILES['fileasar']['size'];
    $file_name = $_FILES['fileasar']['name'];
    $file_type = $_FILES['fileasar']['type'];
    $tmpname = $_FILES["fileasar"]["tmp_name"];
    $file_size_word = $_FILES['fileasar_word']['size'];
    $file_name_word = $_FILES['fileasar_word']['name'];
    $file_type_word = $_FILES['fileasar_word']['type'];
    $allowed_pdf = array('pdf');
    $ext_pdf = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed = array('docx', 'doc');
    $ext = pathinfo($file_name_word, PATHINFO_EXTENSION);
    $tmpname_word = $_FILES["fileasar_word"]["tmp_name"];
    $codeasar = $_POST['codeasar'];
    $adhesive = $_SESSION['username'];
    $asar_city = $_SESSION['state'];
    if ($file_size > 20971520 or $file_size_word > 20971520) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfilesize");
    } elseif ($file_size == 0 and $file_size_word == 0) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfile");
    } elseif (!in_array($ext_pdf, $allowed_pdf) and !in_array($ext, $allowed)) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfiletype");
    } else {
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files/" . $file_name);
        }
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word)) {
            unlink(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
        }
        if (!empty($_FILES['fileasar']) and (empty($_FILES['fileasar_word']) or $file_name_word == '')) {
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif (!empty($_FILES['fileasar_word']) and (empty($_FILES['fileasar']) or $file_name == '')) {
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            mysqli_query($connection, "update etelaat_a set fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif ((!empty($_FILES['fileasar_word']) or $file_name_word == '') and (!empty($_FILES['fileasar']) or $file_name == '')) {
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } else {
            header("location:" . $main_website_url . "attach_file_to_asar.php?findwithname");
        }
    }
}
//end inc attach file admin for asar

//inc for attach file type2 to asar
elseif (isset($_POST['attachfile_type2']) and !empty($_FILES['fileasar']) and !empty($_POST['codeasar'])) {
    $operation = "attachfile_type2";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $file_size = $_FILES['fileasar']['size'];
    $file_name = $_FILES['fileasar']['name'];
    $file_type = $_FILES['fileasar']['type'];
    $tmpname = $_FILES["fileasar"]["tmp_name"];
    $file_size_word = $_FILES['fileasar_word']['size'];
    $file_name_word = $_FILES['fileasar_word']['name'];
    $file_type_word = $_FILES['fileasar_word']['type'];
    $allowed_pdf = array('pdf');
    $ext_pdf = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed = array('docx', 'doc');
    $ext = pathinfo($file_name_word, PATHINFO_EXTENSION);
    $filename_without_extpdf = pathinfo($file_name, PATHINFO_FILENAME);
    $filename_without_extword = pathinfo($file_name_word, PATHINFO_FILENAME);
    $tmpname_word = $_FILES["fileasar_word"]["tmp_name"];
    $codeasar = $_POST['codeasar'];
    $nameasar = $_POST['nameasar'];
    $adhesive = $_SESSION['username'];
    $adhesive_city = $_SESSION['city'];
    $asar_city = @$_SESSION['state'];
    $query = mysqli_query($connection, "select * from rater_list inner join etelaat_p on rater_list.city_name=etelaat_p.ostantahsili where rater_list.username='$adhesive' and rater_list.city_name=etelaat_p.ostantahsili");
    foreach ($query as $selectforcheckratercity) {
    }
    if ($file_size > 20971520 or $file_size_word > 20971520) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfilesize");
    } elseif ($file_size == 0 and $file_size_word == 0) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfile");
    } elseif (!in_array($ext_pdf, $allowed_pdf) and !in_array($ext, $allowed)) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfiletype");
    } elseif ($selectforcheckratercity == NULL) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?unknownwrong");
    } elseif ($filename_without_extpdf != $codeasar and $filename_without_extword != $codeasar) {
        if ($filename_without_extword != $codeasar) {
            header("location:" . $main_website_url . "attach_file_to_asar.php?invaliddocname");
        }
        if ($filename_without_extpdf != $codeasar) {
            header("location:" . $main_website_url . "attach_file_to_asar.php?invalidpdfname");
        }
    } else {
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files/" . $file_name)) {
            @unlink(__DIR__ . "/../../dist/files/asar_files/" . $file_name);
        }
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word)) {
            @unlink(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
        }
        if (isset($_FILES['fileasar_word']) and !isset($_FILES['fileasar'])) {
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',`fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif (!isset($_FILES['fileasar_word']) and isset($_FILES['fileasar'])) {
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif (isset($_FILES['fileasar_word']) and isset($_FILES['fileasar'])) {
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',`fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } else {
            header("location:" . $main_website_url . "attach_file_to_asar.php?findwithname");
        }
    }
}
//end inc attach file type2 for asar

//inc for attach file type3 to asar
elseif (isset($_POST['attachfile_type3']) and !empty($_FILES['fileasar']) and !empty($_POST['codeasar'])) {
    $operation = "attachfile_type3";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
    $usercode = $_SESSION['username'];

    $file_size = $_FILES['fileasar']['size'];
    $file_name = $_FILES['fileasar']['name'];
    $file_type = $_FILES['fileasar']['type'];
    $tmpname = $_FILES["fileasar"]["tmp_name"];
    $file_size_word = $_FILES['fileasar_word']['size'];
    $file_name_word = $_FILES['fileasar_word']['name'];
    $file_type_word = $_FILES['fileasar_word']['type'];
    $allowed_pdf = array('pdf');
    $ext_pdf = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed = array('docx', 'doc');
    $ext = pathinfo($file_name_word, PATHINFO_EXTENSION);
    $tmpname_word = $_FILES["fileasar_word"]["tmp_name"];
    $codeasar = $_POST['codeasar'];
    $nameasar = $_POST['nameasar'];
    $adhesive = $_SESSION['username'];
    $adhesive_city = $_POST['adhesive_city'];
    $asar_city = $_SESSION['state'];
    $filename_without_extpdf = pathinfo($file_name, PATHINFO_FILENAME);
    $filename_without_extword = pathinfo($file_name_word, PATHINFO_FILENAME);
    $query = mysqli_query($connection, "select * from rater_list inner join etelaat_p on rater_list.city_name=etelaat_p.ostantahsili where rater_list.username='$adhesive' and rater_list.city_name=etelaat_p.ostantahsili");
    foreach ($query as $selectforcheckratercity) {
    }
    if ($file_size > 20971520 or $file_size_word > 20971520) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfilesize");
    } elseif ($file_size == 0 and $file_size_word == 0) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfile");
    } elseif (!in_array($ext_pdf, $allowed_pdf) and !in_array($ext, $allowed)) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?wrongfiletype");
    } elseif ($selectforcheckratercity == NULL) {
        header("location:" . $main_website_url . "attach_file_to_asar.php?unknownwrong");
    } elseif ($filename_without_extpdf != $codeasar and $filename_without_extword != $codeasar) {
        if ($filename_without_extword != $codeasar) {
            header("location:" . $main_website_url . "attach_file_to_asar.php?invaliddocname");
        }
        if ($filename_without_extpdf != $codeasar) {
            header("location:" . $main_website_url . "attach_file_to_asar.php?invalidpdfname");
        }
    } else {
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files/" . $file_name);
        }
        if (!file_exists(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word)) {
            unlink(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
        }
        if (!empty($_FILES['fileasar']) and (empty($_FILES['fileasar_word']) or $file_name_word == '')) {
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',`fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set vaziatmadreseasar='انتقال از استان',transporter_to_school_user='$usercode',date_transfer_to_school='$date',vaziatkarnamemadrese='در حال ارزیابی',nobat_arzyabi_madrese='ارزیابی اجمالی',nobat_arzyabi_ostani=null where codeasar='$codeasar'");
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif (!empty($_FILES['fileasar_word']) and (empty($_FILES['fileasar']) or $file_name == '')) {
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            mysqli_query($connection, "update etelaat_a set vaziatmadreseasar='انتقال از استان',transporter_to_school_user='$usercode',date_transfer_to_school='$date',vaziatkarnamemadrese='در حال ارزیابی',nobat_arzyabi_madrese='ارزیابی اجمالی',nobat_arzyabi_ostani=null where codeasar='$codeasar'");
            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } elseif ((!empty($_FILES['fileasar_word']) or $file_name_word == '') and (!empty($_FILES['fileasar']) or $file_name == '')) {
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',`fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar' ");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set nameasar='$nameasar',fileasar_word= 'dist/files/asar_files_word/$file_name_word',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where codeasar='$codeasar' ");
            move_uploaded_file($tmpname_word, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name_word);
            mysqli_query($connection, "update etelaat_a set vaziatmadreseasar='انتقال از استان',transporter_to_school_user='$usercode',date_transfer_to_school='$date',vaziatkarnamemadrese='در حال ارزیابی',nobat_arzyabi_madrese='ارزیابی اجمالی',nobat_arzyabi_ostani=null where codeasar='$codeasar'");

            header("location:" . $main_website_url . "attach_file_to_asar.php?filesset&code=$codeasar");
        } else {
            header("location:" . $main_website_url . "attach_file_to_asar.php?findwithname");
        }
    }
}
//end inc attach file type3 for asar

//start move to school
elseif (isset($_POST['move_to_school']) and !empty($_POST['schoolname'])) {
    $operation = "move_to_school";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $school = $_POST['schoolname'];
    $state = $_SESSION['city'];
    $city = $_SESSION['shahr_name'];
    $usercode = $_SESSION['username'];
    switch ($city) {
        case 'بناب':
            $query = mysqli_query($connection, "select * from etelaat_p inner join etelaat_a on etelaat_p.codeasar=etelaat_a.codeasar where etelaat_p.shahrtahsili='بناب' and (etelaat_a.vaziatkarnamemadrese is null or etelaat_a.vaziatkarnamemadrese='') and (etelaat_a.vaziatmadreseasar is null or etelaat_a.vaziatmadreseasar='') and etelaat_p.madrese='$school' and etelaat_a.nameasar is not null and etelaat_a.nameasar!='' and etelaat_a.vaziatkarnameostani='در حال ارزیابی' and (fileasar is not null or fileasar_word is not null)");
            break;
        case 'کاشان':
            $query = mysqli_query($connection, "select * from etelaat_p inner join etelaat_a on etelaat_p.codeasar=etelaat_a.codeasar where etelaat_p.shahrtahsili='کاشان' and (etelaat_a.vaziatkarnamemadrese is null or etelaat_a.vaziatkarnamemadrese='') and (etelaat_a.vaziatmadreseasar is null or etelaat_a.vaziatmadreseasar='') and etelaat_p.madrese='$school' and etelaat_a.nameasar is not null and etelaat_a.nameasar!='' and etelaat_a.vaziatkarnameostani='در حال ارزیابی' and (fileasar is not null or fileasar_word is not null)");
            break;
        default:
            $query = mysqli_query($connection, "select * from etelaat_p inner join etelaat_a on etelaat_p.codeasar=etelaat_a.codeasar where etelaat_p.shahrtahsili!='بناب' and etelaat_p.shahrtahsili!='کاشان' and etelaat_p.ostantahsili='$state' and (etelaat_a.vaziatkarnamemadrese is null or etelaat_a.vaziatkarnamemadrese='') and (etelaat_a.vaziatmadreseasar is null or etelaat_a.vaziatmadreseasar='') and etelaat_a.vaziatkarnameostani='در حال ارزیابی' and etelaat_p.madrese='$school' and (fileasar is not null or fileasar_word is not null)");
            break;
    }
    foreach ($query as $item) {
        $codeasar = $item['codeasar'];
        mysqli_query($connection, "update etelaat_a set vaziatmadreseasar='انتقال از استان',transporter_to_school_user='$usercode',date_transfer_to_school='$date',vaziatkarnamemadrese='در حال ارزیابی',nobat_arzyabi_madrese='ارزیابی اجمالی',nobat_arzyabi_ostani=null where codeasar='$codeasar'");
    }
    $num = mysqli_num_rows($query);
    header("location:" . $main_website_url . "/../../move_to_school.php?moved&num=$num");
}
//end move to school

//start setting new asar
elseif (isset($_POST['set_new_asar']) and !empty($_SESSION['username']) and !empty($_POST['codemelli']) and !empty($_POST['advaar'])) {
    $operation = "set_new_asar";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $dore = $_POST['advaar'];
    if ($dore == "انتخاب کنید") {
        $dore = NULL;
    }
    $asarname = $_POST['asarname'];
    $noefaaliat = $_POST['noefaaliat'];
    if ($noefaaliat == "انتخاب کنید") {
        $noefaaliat = NULL;
    }
    $ghalebpazhouhesh = $_POST['ghalebpazhouhesh'];
    if ($ghalebpazhouhesh == "انتخاب کنید") {
        $ghalebpazhouhesh = NULL;
    }
    $satharzyabi = $_POST['satharzyabi'];
    if ($satharzyabi == "انتخاب کنید") {
        $satharzyabi = NULL;
    }
    $elmigroup = $_POST['elmigroup'];
    if ($elmigroup == "انتخاب کنید") {
        $elmigroup = NULL;
    }
    $noepazhouhesh = $_POST['noepazhouhesh'];
    if ($noepazhouhesh == "انتخاب کنید") {
        $noepazhouhesh = NULL;
    }
    $vaziatnashr = $_POST['vaziatnashr'];
    if ($vaziatnashr == "انتخاب کنید") {
        $vaziatnashr = NULL;
    }
    $tedadsafhe = $_POST['tedadsafhe'];
    $bakhshvizheh = $_POST['bakhshvizheh'];
    if ($bakhshvizheh == "انتخاب کنید") {
        $bakhshvizheh = NULL;
    }
    $vaziatostani = $_POST['vaziatostani'];
    if ($vaziatostani == "انتخاب کنید") {
        $vaziatostani = NULL;
    }
    $sharayet = $_POST['sharayetavvalie'];
    if ($sharayet == "انتخاب کنید") {
        $sharayet = NULL;
    }
    $ellat = $_POST['ellatnadashtansharayet'];
    if ($ellat == "انتخاب کنید") {
        $ellat = NULL;
    }
    $bargozide = $_POST['bargozide'];
    if ($bargozide == "انتخاب کنید") {
        $bargozide = NULL;
    }
    $namesahebasar = $_POST['namesahebasar'];
    $familysahebasar = $_POST['familysahebasar'];
    $fathername = $_POST['fathersahebasar'];
    $codemelli = $_POST['codemelli'];
    $gender = $_POST['gender'];
    if ($gender == "انتخاب کنید") {
        $gender = NULL;
    }
    $shartsenni = $_POST['shartsenni'];
    if ($shartsenni == "انتخاب کنید") {
        $shartsenni = NULL;
    }
    $vaziattaahol = $_POST['vaziattaahol'];
    if ($vaziattaahol == "انتخاب کنید") {
        $vaziattaahol = NULL;
    }
    $ostansokoonat = $_POST['ostansokoonat'];
    $shahrsokoonat = $_POST['shahrsokoonat'];
    $address = $_POST['address'];
    $codeposti = $_POST['codeposti'];
    $codeshahr = $_POST['codeshahr'];
    $telephone = $_POST['telephone'];
    $ostantahsil = $_POST['ostantahsil'];
    $shahrtahsil = $_POST['shahrtahsil'];
    $madrese = $_POST['namemadrese'];
    $paye = $_POST['paye'];
    $sath = $_POST['sath'];
    $term = $_POST['term'];
    $tarikhtavallod = $_POST['t_year'] . "/" . $_POST['t_month'] . "/" . $_POST['t_day'];
    $tahsilatghhozavi = $_POST['tahsilatgheirhozavi'];
    $reshtedaneshgahi = $_POST['reshtedaneshgahi'];
    $id_parvande = $_POST['id_parvandetahsili'];
    $id_shenasname = $_POST['id_shenasname'];
    $mahalsodoor = $_POST['mahalsodoor'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $reshtetakhasosihozavi = $_POST['reshtetakhasosihozavi'];
    $markaztakhasosihozavi = $_POST['markaztakhasosihozavi'];
    $meliat = $_POST['meliat'];
    $namekeshvar = $_POST['namekeshvar'];
    $gozarname = $_POST['gozarname'];
    $namemarkaztahsili = $_POST['namemarkaztahsili'];
    $noetahsilathozavi = $_POST['noetahsilathozavi'];
    $akhzmadrak = $_POST['akhzmadrakgheirhozavi'];
    $usersabt = $_SESSION['username'];
    $query2 = mysqli_query($connection, "select max(codeasar) from etelaat_a");
    foreach ($query2 as $selectmax) {
    }
    $codeasar = $selectmax['max(codeasar)'] + 1;
    $insertintoet_a = "insert into etelaat_a(`karbar`,`vaziatostaniasar`,`codeasar`,`nameasar`,`noefaaliat`,`ghalebpazhouhesh`
                        ,`groupelmi`,`bakhshvizheh`,`noepazhouhesh`,`vaziatnashr`,`tedadsafhe`,`sharayetavalliehsherkat`,
                      `ellatnadashtansharayet`,`satharzyabi`,`bargozidehkeshvari`,`jashnvareh`)
                      values ('$usersabt','$vaziatostani','$codeasar','$asarname','$noefaaliat','$ghalebpazhouhesh','$elmigroup',
                              '$bakhshvizheh','$noepazhouhesh','$vaziatnashr','$tedadsafhe','$sharayet','$ellat','$satharzyabi',
                              '$bargozide','$dore')";
    mysqli_query($connection, $insertintoet_a);

    $insertintoet_p = "insert into etelaat_p(`jashnvareh`,`codeasar`,`codemelli`,`fname`,`family`,`father_name`,
                      `tarikhtavallod`,`gender`,`shartsenni`,`vaziattaahol`,`ostansokoonat`,`shahrsokoonat`,
                      `address`,`codeposti`,`codeshahr`,`telephone`,`ostantahsili`,`shahrtahsili`,`madrese`,
                      `paye`,`sath`,`term`,`tahsilatghhozavi`,`reshtedaneshgahi`,`shparvandetahsili`,`sh_shenasnameh`,
                      `mahalsodoor`,`mobile`,`email`,`reshtetakhasosihozavi`,`markaztakhasosihozavi`,`meliat`,`namekeshvar`,
                      `gozarname`,`namemarkaztahsili`,`noetahsilathozavi`,`salakhzmadrakghhozavi`)
                       values ('$dore','$codeasar','$codemelli','$namesahebasar','$familysahebasar','$fathername',
                               '$tarikhtavallod','$gender','$shartsenni','$vaziattaahol','$ostansokoonat',
                               '$shahrsokoonat','$address','$codeposti','$codeshahr','$telephone','$ostantahsil',
                               '$shahrtahsil','$madrese','$paye','$sath','$term','$tahsilatghhozavi','$reshtedaneshgahi',
                               '$id_parvande','$id_shenasname','$mahalsodoor','$mobile','$email','$reshtetakhasosihozavi',
                               '$markaztakhasosihozavi','$meliat','$namekeshvar','$gozarname','$namemarkaztahsili','$noetahsilathozavi',
                               '$akhzmadrak')";
    mysqli_query($connection, $insertintoet_p);
    header("location:" . $main_website_url . "/../../new_info.php?created&code=$codeasar&name=$asarname");
}
//end setting new asar

//start change password
elseif (isset($_POST['changepass']) and !empty($_POST['oldpass']) and !empty($_POST['newpass']) and !empty($_POST['usercode'])) {
    $operation = "changepass";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $usercode = $_SESSION['username'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$usercode'");
    foreach ($result as $results) {
    }
    if ($oldpass != $results['password']) {
        header("location:" . $main_website_url . "/../../profile.php?wrongpass");
    } else {
        mysqli_query($connection, "update rater_list set `password`='$newpass' where `username`='$usercode'");
        header("location:" . $main_website_url . "/../../profile.php?passwordset");
    }
}
//end change password

//start upload file of export raters costs
elseif (isset($_POST['uploader']) and !empty($_FILES['uploadexpexcelraterscost']) and !empty($_POST['jashnvareh'])) {
    $operation = "uploader";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    include_once __DIR__ . '/../../config/connection.php';
    $uploader = $_POST['uploader'];
    $jashnvareh = $_POST['jashnvareh'];
    $file_size = $_FILES['uploadcvfile']['size'];
    if ($file_size > 5242880) {
        header("location:" . $main_website_url . "excel_export/excel_export_with_save_payment.php?wrongexpsize");
    } elseif (!file_exists(__DIR__ . "/../../dist/files/expexcelraterscostfiles/" . $_FILES['uploadexpexcelraterscost']["name"])) {
        $filename = $_FILES['uploadexpexcelraterscost']["name"];
        $path = "dist/files/expexcelraterscostfiles/" . $_FILES["uploadexpexcelraterscost"]["name"];
        mysqli_query($connection, "insert into uploadedexportraterscost (jashnvareh,filename,path,date_uploaded,uploader) values ('$jashnvareh','$filename','$path','$date','$uploader')");
        move_uploaded_file($_FILES['uploadexpexcelraterscost']["tmp_name"], __DIR__ . "/../../dist/files/expexcelraterscostfiles/" . $_FILES["uploadexpexcelraterscost"]["name"]);
        header("location:" . $main_website_url . "excel_export/excel_export_with_save_payment.php?expset");
    } else {
        header("location:" . $main_website_url . "excel_export/excel_export_with_save_payment.php?wrong");
    }
}
//end upload file of export raters costs

//start remove from rated ejmali asar
elseif (isset($_POST['removefromrated']) and !empty($_POST['codeasar']) and !empty($_POST['rater_id']) and !empty($_POST['remover'])) {
    $operation = "removefromrated";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasar'];
    $rater_id = $_POST['rater_id'];
    $remover = $_POST['remover'];
    mysqli_query($connection, "update rater_comments_archive set accept_or_reject=NULL,comment=NULL,rate_remover='$remover',remove_rate_date='$date' where `codeasar`='$codeasar' and `rater_id`='$rater_id'");
    header("location:" . $main_website_url . "/../../arzyabi_shode.php?removedasarfromid");
}
//end remove from rated ejmali asar

//start deactivate keshvari rater
elseif (isset($_POST['deactivateaterkeshvari']) and !empty($_POST['ratercode']) and !empty($_POST['remover'])) {
    $operation = "deactivateaterkeshvari";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $ratercode = $_POST['ratercode'];
    $deactivator = $_POST['deactivator'];
    mysqli_query($connection, "update rater_list set type=9,deactivator='$deactivator',date_deactivated='$date' where username='$ratercode' and 'type'=0 and city_name is null");
    header("location:" . $main_website_url . "/../../rater_pages/deactivate_rater.php?deactivated");
} elseif (isset($_POST['deactivateaterkeshvari']) and empty($_POST['ratercode']) and !empty($_POST['remover'])) {
    header("location:" . $main_website_url . "/../../rater_pages/deactivate_rater.php?nullcode");
}
//end deactivate keshvari rater

//start add keshvari rater
elseif (isset($_POST['addraterkeshvari']) and !empty($_POST['adhesive']) and !empty($_POST['code'])) {
    $operation = "addraterkeshvari";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codearzyab = $_POST['code'];
    $namearzyab = $_POST['name'];
    $familyarzyab = $_POST['family'];
    $sathelmiarzyab = $_POST['sath_elmi'];
    $gender = $_POST['gender'];

    @$adabiat = $_POST['adabiat'];
    @$akhlaghtarbiat = $_POST['akhlaghtarbiat'];
    @$hadisderaye = $_POST['hadisderaye'];
    @$falsafe = $_POST['falsafe'];
    @$tafsir = $_POST['tafsir'];
    @$kalaam = $_POST['kalaam'];
    @$ulumensani = $_POST['ulumensani'];
    @$feghh = $_POST['feghh'];
    @$osoolfegh = $_POST['osoolfegh'];
    @$tarikheslam = $_POST['tarikheslam'];
    @$tashihtaligh = $_POST['tashihtaligh'];
    @$tarjome = $_POST['tarjome'];

    $arzyabsamane = 'ارزیاب جشنواره';
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $accnum = $_POST['acc_number'];
    $bankname = $_POST['bankname'];
    $inputuser = $_POST['adhesive'];

    if ($adabiat == null) {
        $adabiat = NULL;
    }
    if ($akhlaghtarbiat == null) {
        $akhlaghtarbiat = NULL;
    }
    if ($hadisderaye == null) {
        $hadisderaye = NULL;
    }
    if ($falsafe == null) {
        $falsafe = NULL;
    }
    if ($tafsir == null) {
        $tafsir = NULL;
    }
    if ($kalaam == null) {
        $kalaam = NULL;
    }
    if ($ulumensani == null) {
        $ulumensani = NULL;
    }
    if ($feghh == null) {
        $feghh = NULL;
    }
    if ($osoolfegh == null) {
        $osoolfegh = NULL;
    }
    if ($tarikheslam == null) {
        $tarikheslam = NULL;
    }
    if ($bankname == "انتخاب کنید") {
        $bankname = NULL;
    }
    if ($sathelmiarzyab == "انتخاب کنید") {
        $sathelmiarzyab = NULL;
    }
    if ($tashihtaligh == null) {
        $tashihtaligh = NULL;
    }
    if ($tarjome == null) {
        $tarjome = NULL;
    }
    $add = "insert into `rater_list` (`name`,`family`,`code`,`gender`,`sath_elmi`,`adabiat`,`akhlaghtarbiat`,`hadisderaye`,`falsafe`,`tafsir`,`kalaam`,`ulumensani`,`feghh`,`osoolfegh`,`tarikheslam`,`phone`,`address`,`username`,`password`,`account_number`,`bank`,`input_user`,`tashihtaligh`,`tarjome`,`approved`,`subject`,`type`,`date_added`)
                            values ('$namearzyab','$familyarzyab','$codearzyab','$gender','$sathelmiarzyab','$adabiat','$akhlaghtarbiat','$hadisderaye','$falsafe','$tafsir','$kalaam','$ulumensani','$feghh','$osoolfegh','$tarikheslam','$phone','$address','$codearzyab','$password','$accnum','$bankname','$inputuser','$tashihtaligh','$tarjome',1,'$arzyabsamane',0,'$date')";
    mysqli_query($connection, $add);
    header("location:" . $main_website_url . "/../../rater_pages/add_rater.php?successremove");

}
//end add keshvari rater

//start remove asar from rater keshvari panel ejmali
elseif (isset($_POST['rfek']) and !empty($_POST['asarcode']) and !empty($_POST['ratercode'])) {
    $operation = "rfek";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $rater = $_POST['ratercode'];
    mysqli_query($connection, "delete from rater_comments_archive where `codeasar`='$codeasar' and `rater_id`='$rater'");
    header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?removedasarfromid");
}
//end remove asar from rater keshvari panel ejmali

//start remove asar from rater keshvari panel tafsili1
elseif (isset($_POST['rft1k']) and !empty($_POST['asarcode'])) {
    $operation = "rft1k";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili1 is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili1=null,tarikhtahvilasar1=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili1'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?wrongrem");
    }
}
//end remove asar from rater keshvari panel tafsili1

//start remove asar from rater keshvari panel tafsili2
elseif (isset($_POST['rft2k']) and !empty($_POST['asarcode'])) {
    $operation = "rft2k";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili2 is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili2=null,tarikhtahvilasar2=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili2'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?wrongrem");
    }
}
//end remove asar from rater keshvari panel tafsili2

//start remove asar from rater keshvari panel tafsili3
elseif (isset($_POST['rft3k']) and !empty($_POST['asarcode'])) {
    $operation = "rft3k";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select * from etelaat_a where codeasar='$codeasar' and codearzyabtafsili3 is not null");
    foreach ($query as $item) {
    }
    if ($item['codeasar'] != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili3=null,tarikhtahvilasar3=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili3'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?wrongrem");
    }
}
//end remove asar from rater keshvari panel tafsili3

//start remove asar from rater ostani panel ejmali
elseif (isset($_POST['rfeo']) and !empty($_POST['asarcode'])) {
    $operation = "rfeo";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabejmali_ostani is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabejmali_ostani=null,tahvilasararzyabiejmali_ostani=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?removedasarfromid");
    } elseif ($item['codearzyabejmali_ostani'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?wrongrem");
    }
}
//end remove asar from rater ostani panel ejmali

//start remove asar from rater ostani panel tafsili1
elseif (isset($_POST['rft1o']) and !empty($_POST['asarcode'])) {
    $operation = "rft1o";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili1_ostani is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili1_ostani=null,tahvilasararzyabitafsili1_ostani=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili1_ostani'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?wrongrem");
    }
}
//end remove asar from rater ostani panel tafsili1

//start remove asar from rater ostani panel tafsili2
elseif (isset($_POST['rft2o']) and !empty($_POST['asarcode'])) {
    $operation = "rft2o";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili2_ostani is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili2_ostani=null,tahvilasararzyabitafsili2_ostani=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili2_ostani'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?wrongrem");
    }
}
//end remove asar from rater ostani panel tafsili2

//start remove asar from rater ostani panel tafsili3
elseif (isset($_POST['rft3o']) and !empty($_POST['asarcode'])) {
    $operation = "rft3o";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili3_ostani is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili3_ostani=null,tahvilasararzyabitafsili3_ostani=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili3_ostani'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?wrongrem");
    }
}
//end remove asar from rater ostani panel tafsili3

//start remove asar from rater ostani panel ejmali
elseif (isset($_POST['rfem']) and !empty($_POST['asarcode'])) {
    $operation = "rfem";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabejmali_madrese is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabejmali_madrese=null,tahvilasararzyabiejmali_madrese=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?removedasarfromid");
    } elseif ($item['codearzyabejmali_madrese'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_ejmali.php?wrongrem");
    }
}
//end remove asar from rater ostani panel ejmali

//start remove asar from rater ostani panel tafsili1
elseif (isset($_POST['rft1m']) and !empty($_POST['asarcode'])) {
    $operation = "rft1m";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili1_madrese is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili1_madrese=null,tahvilasararzyabitafsili1_madrese=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili1_madrese'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili1.php?wrongrem");
    }
}
//end remove asar from rater ostani panel tafsili1

//start remove asar from rater ostani panel tafsili2
elseif (isset($_POST['rft2m']) and !empty($_POST['asarcode'])) {
    $operation = "rft2m";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili2_madrese is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili2_madrese=null,tahvilasararzyabitafsili2_madrese=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili2_madrese'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili2.php?wrongrem");
    }
}
//end remove asar from rater ostani panel tafsili2

//start remove asar from rater ostani panel tafsili3
elseif (isset($_POST['rft3m']) and !empty($_POST['asarcode'])) {
    $operation = "rft3m";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['asarcode'];
    $query = mysqli_query($connection, "Select codeasar from etelaat_a where codeasar='$codeasar' and codearzyabtafsili3_madrese is not null");
    foreach ($query as $item) {
    }
    if ($item != null) {
        mysqli_query($connection, "update etelaat_a set codearzyabtafsili3_madrese=null,tahvilasararzyabitafsili3_madrese=null where codeasar='$codeasar'");
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?removedasarfromid");
    } elseif ($item['codearzyabtafsili3_madrese'] == null) {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?raternotfound");
    } else {
        header("location:" . $main_website_url . "/../../set_asar_for_rater_tafsili3.php?wrongrem");
    }

}
//end remove asar from rater ostani panel tafsili3

//start paziresh
elseif (isset($_POST['paziresh']) and !empty($_POST['codeasarfield']) and !empty($_SESSION['username'])) {
    $operation = "paziresh";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
    //start table 1
//	echo "<pre>";
//	print_r($_POST);
//	echo "</pre>";
    $codeasar = $_POST['codeasarfield'];
    $asarname = $_POST['asarname'];
    $noefaaliat = $_POST['noefaaliat'];
    $ghalebpazhouhesh = $_POST['ghalebpazhouhesh'];
    $satharzyabi = $_POST['satharzyabi'];
    $elmigroup = $_POST['elmigroup'];
    $noepazhouhesh = $_POST['noepazhoohesh'];
    $vaziatnashr = $_POST['vaziatnashr'];
    $vaziatpazireshasar = $_POST['vaziatnashr'];
    $tedadsafhe = $_POST['tedadsafhe'];
    $bakhshvizheh = $_POST['bakhshvizheh'];
    $vaziatpazireshasar = $_POST['vaziatpaziresh'];
    $sharayetavvaliehsherkatasar = $_POST['sharayetavalieh'];
    $ellat = $_POST['ellat'];
    $vaziatmadreseasar = $_POST['vaziatmadreseasar'];
    $bargozideh_madrese = $_POST['bargozideh_madrese'];
    $jamemtiazmadrese = $_POST['jamemtiazmadrese'];
    $vaziatostaniasar = $_POST['vaziatostani'];
    $bargozideh_ostani = $_POST['bargozideh_ostani'];
    $approve_sianat = $_POST['approve_sianat'];
    $jamemtiazostan = $_POST['jamemtiazostan'];
    $bargozidehkeshvariasar = $_POST['bargozidehkeshvari'];
    $emtiaznahaei = $_POST['emtiaznahaei'];
    $karbar = $_SESSION['username'];
    $bargozidehkeshvari = $_POST['bargozidehkeshvari'];
    if ($ghalebpazhouhesh == 'انتخاب کنید') {
        $ghalebpazhouhesh = null;
    }
    if ($elmigroup == 'انتخاب کنید') {
        $elmigroup = null;
    }
    if ($bargozidehkeshvariasar == "") {
        $bargozidehkeshvariasar = NULL;
    }
    if ($sharayetavvaliehsherkatasar == "انتخاب کنید") {
        $sharayetavvaliehsherkatasar = NULL;
    }
    if ($ellat == "انتخاب کنید") {
        $ellatasar = null;
    }
    if ($vaziatpazireshasar == "انتخاب کنید") {
        $vaziatpazireshasar = NULL;
    }
    if ($vaziatostaniasar == "انتخاب کنید") {
        $vaziatostaniasar = NULL;
    }
    if ($bargozideh_madrese == '') {
        $bargozideh_madrese = NULL;
    }
    if ($bargozideh_ostani == '') {
        $bargozideh_ostani = NULL;
    }
    if ($approve_sianat == '') {
        $approve_sianat = NULL;
    }

    if ($bargozidehkeshvari == '') {
        $bargozidehkeshvari = NULL;
    }
    if (is_numeric($emtiaznahaei) != 1) {
        $emtiaznahaei = NULL;
    }
    if (is_numeric($jamemtiazmadrese) != 1) {
        $jamemtiazmadrese = NULL;
    }
    if (is_numeric($jamemtiazostan) != 1) {
        $jamemtiazostan = NULL;
    }
    mysqli_query($connection, "update `etelaat_a` set
                   nameasar='$asarname',
                   noefaaliat='$noefaaliat',
                   ghalebpazhouhesh='$ghalebpazhouhesh',
                   satharzyabi='$satharzyabi',
                   groupelmi='$elmigroup',
                   noepazhouhesh='$noepazhouhesh',
                   vaziatnashr='$vaziatnashr',
                   tedadsafhe='$tedadsafhe',
                   bakhshvizheh='$bakhshvizheh',
                   vaziatpazireshasar='$vaziatpazireshasar',
                   sharayetavalliehsherkat='$sharayetavvaliehsherkatasar',
                   ellatnadashtansharayet='$ellat',
                    emtiaznahaei='$emtiaznahaei',
                    jamemtiazmadrese='$jamemtiazmadrese',
                   vaziatmadreseasar='$vaziatmadreseasar',
                   bargozideh_madrese='$bargozideh_madrese',
                   vaziatostaniasar='$vaziatostaniasar',
                   bargozideh_ostani='$bargozideh_ostani',
                   approve_sianat='$approve_sianat',
                   jamemtiazostan='$jamemtiazostan',
                   bargozidehkeshvari='$bargozidehkeshvari',
                   karbar='$karbar',
                   edit_date='$datewithtime' where codeasar='$codeasar'");
    //end table 1
//	//start table 2
    $fname = $_POST['fname'];
    $family = $_POST['family'];
    $father_name = $_POST['father_name'];
    $codemelli = $_POST['codemelli'];
    $tarikhtavallod = $_POST['tarikhtavallod'];
    $gender = $_POST['gender'];
    $vaziattaahol = $_POST['vaziattaahol'];
    $state_custom = $_POST['state_custom'];
    $city_custom = $_POST['city_custom'];
    $madrese = $_POST['madrese'];
    $mobile = $_POST['mobile'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $paye = $_POST['paye'];
    $sath = $_POST['sath'];
    $term = $_POST['term'];
    $email = $_POST['email'];
    $meliat = $_POST['meliat'];
    $namekeshvar = $_POST['namekeshvar'];
    $gozarname = $_POST['gozarname'];
    $reshtetakhasosihozavi = $_POST['reshtetakhasosihozavi'];
    $markaztakhasosihozavi = $_POST['markaztakhasosihozavi'];
    $namemarkaztahsili = $_POST['namemarkaztahsili'];
    $noetahsilathozavi = $_POST['noetahsilathozavi'];
    $tahsilatghhozavi = $_POST['tahsilatghhozavi'];
    $reshtedaneshgahi = $_POST['reshtedaneshgahi'];
    $shparvandetahsili = $_POST['shparvandetahsili'];
    $salakhzmadrakghhozavi = $_POST['salakhzmadrakghhozavi'];
    $master = $_POST['master'];
    $mastercode = $_POST['mastercode'];
    mysqli_query($connection, "update etelaat_p set fname='$fname',family='$family',father_name='$father_name',codemelli='$codemelli',tarikhtavallod='$tarikhtavallod',
									gender='$gender',vaziattaahol='$vaziattaahol',ostantahsili='$state_custom',shahrtahsili='$city_custom',madrese='$madrese',
									mobile='$mobile',telephone='$telephone',address='$address',paye='$paye',sath='$sath',term='$term',email='$email',
									meliat='$meliat',namekeshvar='$namekeshvar',gozarname='$gozarname',reshtetakhasosihozavi='$reshtetakhasosihozavi',
									markaztakhasosihozavi='$markaztakhasosihozavi',namemarkaztahsili='$namemarkaztahsili',noetahsilathozavi='$noetahsilathozavi',
									tahsilatghhozavi='$tahsilatghhozavi',reshtedaneshgahi='$reshtedaneshgahi',shparvandetahsili='$shparvandetahsili',
									salakhzmadrakghhozavi='$salakhzmadrakghhozavi',master='$master',mastercode='$mastercode' where codeasar='$codeasar'");
    //end table 2
    //start table 3
    $nobat_arzyabi_madrese = $_POST['nobat_arzyabi_madrese'];
    $codearzyabejmali_madrese = $_POST['codearzyabejmali_madrese'];
    $codearzyabtafsili1_madrese = $_POST['codearzyabtafsili1_madrese'];
    $codearzyabtafsili2_madrese = $_POST['codearzyabtafsili2_madrese'];
    $codearzyabtafsili3_madrese = $_POST['codearzyabtafsili3_madrese'];
    $vaziatkarnamemadrese = $_POST['vaziatkarnamemadrese'];
    if ($nobat_arzyabi_madrese == '' or $nobat_arzyabi_madrese == NULL) {
        $nobat_arzyabi_madrese = NULL;
    }
    if ($codearzyabejmali_madrese == '') {
        $codearzyabejmali_madrese = NULL;
    }
    if ($codearzyabtafsili1_madrese == '') {
        $codearzyabtafsili1_madrese = NULL;
    }
    if ($codearzyabtafsili2_madrese == '') {
        $codearzyabtafsili2_madrese = NULL;
    }
    if ($codearzyabtafsili3_madrese == '') {
        $codearzyabtafsili3_madrese = NULL;
    }
    if ($vaziatkarnamemadrese == '') {
        $vaziatkarnamemadrese = NULL;
    }
    mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='$nobat_arzyabi_madrese',codearzyabejmali_madrese='$codearzyabejmali_madrese',codearzyabtafsili1_madrese='$codearzyabtafsili1_madrese',codearzyabtafsili2_madrese='$codearzyabtafsili2_madrese',codearzyabtafsili3_madrese='$codearzyabtafsili3_madrese',vaziatkarnamemadrese='$vaziatkarnamemadrese' where codeasar='$codeasar'");
    //end table 3
    //start table 4
    $nobat_arzyabi_ostani = $_POST['nobat_arzyabi_ostani'];
    $codearzyabejmali_ostani = $_POST['codearzyabejmali_ostani'];
    $codearzyabtafsili1_ostani = $_POST['codearzyabtafsili1_ostani'];
    $codearzyabtafsili2_ostani = $_POST['codearzyabtafsili2_ostani'];
    $codearzyabtafsili3_ostani = $_POST['codearzyabtafsili3_ostani'];
    $vaziatkarnameostani = $_POST['vaziatkarnameostani'];
    if ($nobat_arzyabi_ostani == '' or $nobat_arzyabi_ostani == NULL) {
        $nobat_arzyabi_ostani = NULL;
    }
    if ($codearzyabejmali_ostani == '') {
        $codearzyabejmali_ostani = NULL;
    }
    if ($codearzyabtafsili1_ostani == '') {
        $codearzyabtafsili1_ostani = NULL;
    }
    if ($codearzyabtafsili2_ostani == '') {
        $codearzyabtafsili2_ostani = NULL;
    }
    if ($codearzyabtafsili3_ostani == '') {
        $codearzyabtafsili3_ostani = NULL;
    }
    if ($vaziatkarnameostani == '') {
        $vaziatkarnameostani = NULL;
    }
    mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='$nobat_arzyabi_ostani',codearzyabejmali_ostani='$codearzyabejmali_ostani',codearzyabtafsili1_ostani='$codearzyabtafsili1_ostani',codearzyabtafsili2_ostani='$codearzyabtafsili2_ostani',codearzyabtafsili3_ostani='$codearzyabtafsili3_ostani',vaziatkarnameostani='$vaziatkarnameostani' where codeasar='$codeasar'");
    //end table 4
    //start table 5
    $nobat_arzyabi = $_POST['nobat_arzyabi'];
    $codearzyabejmali = $_POST['codearzyabejmali'];
    $codearzyabtafsili1 = $_POST['codearzyabtafsili1'];
    $codearzyabtafsili2 = $_POST['codearzyabtafsili2'];
    $codearzyabtafsili3 = $_POST['codearzyabtafsili3'];
    $vaziatkarname = $_POST['vaziatkarname'];
    if ($nobat_arzyabi == '' or $nobat_arzyabi == NULL) {
        $nobat_arzyabi = null;
    }
    if ($codearzyabejmali == '') {
        $codearzyabejmali = NULL;
    }
    if ($codearzyabtafsili1 == '') {
        $codearzyabtafsili1 = NULL;
    }
    if ($codearzyabtafsili2 == '') {
        $codearzyabtafsili2 = NULL;
    }
    if ($codearzyabtafsili3 == '') {
        $codearzyabtafsili3 = NULL;
    }
    mysqli_query($connection, "update etelaat_a set nobat_arzyabi='$nobat_arzyabi',codearzyabejmali='$codearzyabejmali',codearzyabtafsili1='$codearzyabtafsili1',codearzyabtafsili2='$codearzyabtafsili2',codearzyabtafsili3='$codearzyabtafsili3',vaziatkarname='$vaziatkarname' where codeasar='$codeasar'");
//	end table 5
    $adhesive = $_SESSION['username'];
//start table6 - pdf
    if ($_FILES['fileasar']['name'] != null) {
        $operation = "AttachFile_PDF_From_Edit";
        mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
        $file_size = $_FILES['fileasar']['size'];
        $file_name = $_FILES['fileasar']['name'];
        $file_type = $_FILES['fileasar']['type'];
        $tmpname = $_FILES["fileasar"]["tmp_name"];
        $allowed_pdf = array('pdf');
        $ext_pdf = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename_without_extpdf = pathinfo($file_name, PATHINFO_FILENAME);
        if ($file_size > 20971520) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileTooBig&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($file_size == 0) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileHaveNotSize&codeasar=$codeasar&nameasar=$asarname");
        } elseif (!in_array($ext_pdf, $allowed_pdf)) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetFileIsNotPDF&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($filename_without_extpdf <> $codeasar) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileNotEq&codeasar=$codeasar&nameasar=$asarname");
        } elseif (file_exists(__DIR__ . "/../../dist/files/asar_files/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
        } else {
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
        }
    }
    //end table6 - pdf

    if ($_FILES['fileasar_word']['name'] != null) {
//    //start table7 - word
        $operation = "AttachFile_WORD_From_Edit";
        mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
        $file_size = $_FILES['fileasar_word']['size'];
        $file_name = $_FILES['fileasar_word']['name'];
        $file_type = $_FILES['fileasar_word']['type'];
        $tmpname = $_FILES["fileasar_word"]["tmp_name"];
        $allowed_word = array('doc', 'docx');
        $ext_word = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename_without_extword = pathinfo($file_name, PATHINFO_FILENAME);
        if ($file_size > 20971520) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileTooBig&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($file_size == 0) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileHaveNotSize&codeasar=$codeasar&nameasar=$asarname");
        } elseif (!in_array($ext_word, $allowed_word)) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetFileIsNotWORD&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($filename_without_extword <> $codeasar) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileNotEq&codeasar=$codeasar&nameasar=$asarname");
        } elseif (file_exists(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
            mysqli_query($connection, "update etelaat_a set `fileasar_word`= 'dist/files/asar_files_word/$file_name',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
        } else {
            mysqli_query($connection, "update etelaat_a set `fileasar_word`= 'dist/files/asar_files_word/$file_name',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
        }
    }
    //end table7 - word
    if ($_FILES['fileasar_word']['name'] == null and $_FILES['fileasar']['name'] == null) {
        header("location:" . $main_website_url . "/../../edit_asar.php?pazireshset&codeasar=$codeasar&nameasar=$asarname");
    }

    header("location:" . $main_website_url . "/../../edit_asar.php?pazireshset&codeasar=$codeasar&nameasar=$asarname");

}
//end paziresh

//start PazireshCity
elseif (isset($_POST['pazireshcity']) and !empty($_POST['codeasarfield']) and !empty($_SESSION['username'])) {
    $operation = "paziresh";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
    //start table 1
//	echo "<pre>";
//	print_r($_POST);
//	echo "</pre>";
    $codeasar = $_POST['codeasarfield'];
    $asarname = $_POST['asarname'];
    $noefaaliat = $_POST['noefaaliat'];
    $ghalebpazhouhesh = $_POST['ghalebpazhouhesh'];
    $satharzyabi = $_POST['satharzyabi'];
    $elmigroup = $_POST['elmigroup'];
    $noepazhouhesh = $_POST['noepazhoohesh'];
    $vaziatnashr = $_POST['vaziatnashr'];
    $vaziatpazireshasar = $_POST['vaziatnashr'];
    $tedadsafhe = $_POST['tedadsafhe'];
    $bakhshvizheh = $_POST['bakhshvizheh'];
    $vaziatpazireshasar = $_POST['vaziatpaziresh'];
    $sharayetavvaliehsherkatasar = $_POST['sharayetavalieh'];
    $ellat = $_POST['ellat'];
    $vaziatmadreseasar = $_POST['vaziatmadreseasar'];
    $bargozideh_madrese = $_POST['bargozideh_madrese'];
    $jamemtiazmadrese = $_POST['jamemtiazmadrese'];
    $vaziatostaniasar = $_POST['vaziatostani'];
    $bargozideh_ostani = $_POST['bargozideh_ostani'];
    $approve_sianat = $_POST['approve_sianat'];
    $jamemtiazostan = $_POST['jamemtiazostan'];
    $bargozidehkeshvariasar = $_POST['bargozidehkeshvari'];
    $emtiaznahaei = $_POST['emtiaznahaei'];
    $karbar = $_SESSION['username'];
    $bargozidehkeshvari = $_POST['bargozidehkeshvari'];
    if ($ghalebpazhouhesh == 'انتخاب کنید') {
        $ghalebpazhouhesh = null;
    }
    if ($elmigroup == 'انتخاب کنید') {
        $elmigroup = null;
    }
    mysqli_query($connection, "update `etelaat_a` set
                   nameasar='$asarname',
                   noefaaliat='$noefaaliat',
                   ghalebpazhouhesh='$ghalebpazhouhesh',
                   satharzyabi='$satharzyabi',
                   groupelmi='$elmigroup',
                   noepazhouhesh='$noepazhouhesh',
                   vaziatnashr='$vaziatnashr',
                   tedadsafhe='$tedadsafhe',
                   bakhshvizheh='$bakhshvizheh',
                   karbar='$karbar',
                   edit_date='$datewithtime' where codeasar='$codeasar'");
    //end table 1
//	//start table 2
    $fname = $_POST['fname'];
    $family = $_POST['family'];
    $father_name = $_POST['father_name'];
    $codemelli = $_POST['codemelli'];
    $tarikhtavallod = $_POST['tarikhtavallod'];
    $gender = $_POST['gender'];
    $vaziattaahol = $_POST['vaziattaahol'];
    $state_custom = $_POST['state_custom'];
    $city_custom = $_POST['city_custom'];
    $madrese = $_POST['madrese'];
    $mobile = $_POST['mobile'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $paye = $_POST['paye'];
    $sath = $_POST['sath'];
    $term = $_POST['term'];
    $email = $_POST['email'];
    $meliat = $_POST['meliat'];
    $namekeshvar = $_POST['namekeshvar'];
    $gozarname = $_POST['gozarname'];
    $reshtetakhasosihozavi = $_POST['reshtetakhasosihozavi'];
    $markaztakhasosihozavi = $_POST['markaztakhasosihozavi'];
    $namemarkaztahsili = $_POST['namemarkaztahsili'];
    $noetahsilathozavi = $_POST['noetahsilathozavi'];
    $tahsilatghhozavi = $_POST['tahsilatghhozavi'];
    $reshtedaneshgahi = $_POST['reshtedaneshgahi'];
    $shparvandetahsili = $_POST['shparvandetahsili'];
    $salakhzmadrakghhozavi = $_POST['salakhzmadrakghhozavi'];
    $master = $_POST['master'];
    $mastercode = $_POST['mastercode'];
    mysqli_query($connection, "update etelaat_p set fname='$fname',family='$family',father_name='$father_name',codemelli='$codemelli',tarikhtavallod='$tarikhtavallod',
									gender='$gender',vaziattaahol='$vaziattaahol',ostantahsili='$state_custom',shahrtahsili='$city_custom',madrese='$madrese',
									mobile='$mobile',telephone='$telephone',address='$address',paye='$paye',sath='$sath',term='$term',email='$email',
									meliat='$meliat',namekeshvar='$namekeshvar',gozarname='$gozarname',reshtetakhasosihozavi='$reshtetakhasosihozavi',
									markaztakhasosihozavi='$markaztakhasosihozavi',namemarkaztahsili='$namemarkaztahsili',noetahsilathozavi='$noetahsilathozavi',
									tahsilatghhozavi='$tahsilatghhozavi',reshtedaneshgahi='$reshtedaneshgahi',shparvandetahsili='$shparvandetahsili',
									salakhzmadrakghhozavi='$salakhzmadrakghhozavi',master='$master',mastercode='$mastercode' where codeasar='$codeasar'");
    //end table 2
    $adhesive = $_SESSION['username'];
    $adhesive_city = @$_SESSION['city'];
    $asar_city = @$_SESSION['state'];
    if ($_FILES['fileasar']['name'] != null) {
        //start table3 - pdf
        $operation = "AttachFile_PDF_From_Edit";
        mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
        $file_size = $_FILES['fileasar']['size'];
        $file_name = $_FILES['fileasar']['name'];
        $file_type = $_FILES['fileasar']['type'];
        $tmpname = $_FILES["fileasar"]["tmp_name"];
        $allowed_pdf = array('pdf');
        $ext_pdf = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename_without_extpdf = pathinfo($file_name, PATHINFO_FILENAME);
        if ($file_size > 20971520) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileTooBig&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($file_size == 0) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileHaveNotSize&codeasar=$codeasar&nameasar=$asarname");
        } elseif (!in_array($ext_pdf, $allowed_pdf)) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetFileIsNotPDF&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($filename_without_extpdf <> $codeasar) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetPDFFileNotEq&codeasar=$codeasar&nameasar=$asarname");
        }
        if (file_exists(__DIR__ . "/../../dist/files/asar_files/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files/" . $file_name);
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
        } else {
            mysqli_query($connection, "update etelaat_a set `fileasar`= 'dist/files/asar_files/$file_name',fileasar_uploader='$adhesive',fileasar_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files/" . $file_name);
        }
        //end table3 - pdf
    }
    if ($_FILES['fileasar_word']['name'] != null) {
        //start table3 - word
        $operation = "AttachFile_WORD_From_Edit";
        mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
        $file_size = $_FILES['fileasar_word']['size'];
        $file_name = $_FILES['fileasar_word']['name'];
        $file_type = $_FILES['fileasar_word']['type'];
        $tmpname = $_FILES["fileasar_word"]["tmp_name"];
        $allowed_word = array('doc', 'docx');
        $ext_word = pathinfo($file_name, PATHINFO_EXTENSION);
        $filename_without_extword = pathinfo($file_name, PATHINFO_FILENAME);
        if ($file_size > 20971520) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileTooBig&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($file_size == 0) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileHaveNotSize&codeasar=$codeasar&nameasar=$asarname");
        } elseif (!in_array($ext_word, $allowed_word)) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetFileIsNotWORD&codeasar=$codeasar&nameasar=$asarname");
        } elseif ($filename_without_extword <> $codeasar) {
            header("location:" . $main_website_url . "/../../edit_asar.php?pazireshsetWORDFileNotEq&codeasar=$codeasar&nameasar=$asarname");
        }
        if (file_exists(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name)) {
            unlink(__DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
            mysqli_query($connection, "update etelaat_a set `fileasar_word`= 'dist/files/asar_files_word/$file_name',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
        } else {
            mysqli_query($connection, "update etelaat_a set `fileasar_word`= 'dist/files/asar_files_word/$file_name',fileasar_word_uploader='$adhesive',fileasar_word_upload_date='$date' where `codeasar`='$codeasar'");
            move_uploaded_file($tmpname, __DIR__ . "/../../dist/files/asar_files_word/" . $file_name);
        }

        //end table3 - word
    }
    header("location:" . $main_website_url . "/../../edit_asar.php?pazireshset&codeasar=$codeasar&nameasar=$asarname");

}
//end PazireshCity

//start admin manager
//start country admins
//start add
elseif (isset($_POST['setadmin']) and !empty($_POST['username'])) {
    $operation = "setadmin";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeadmin = $_POST['username'];
    $nameadmin = $_POST['name'];
    $familyadmin = $_POST['family'];
    $useradmin = $_POST['username'];
    $phone = $_POST['phone'];
    $codemelli = $_POST['codemelli'];
    $password = $_POST['password'];
    $inputuser = $_SESSION['username'];
    $karshenas = 'کارشناس سامانه';
    $subject = $_POST['subject'];
    $ratercode = $_POST['username'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=1");
    foreach ($result as $results) {
    }
    if (empty($results['username'])) {
        $add = "insert into `rater_list` (`name`,`family`,`code`,`phone`,`username`,`password`,`input_user`,`type`,`subject`,approved,date_added)
                                                    values ('$nameadmin','$familyadmin','$codeadmin','$phone','$useradmin','$password','$inputuser',1,'$karshenas',1,'$date')";
        mysqli_query($connection, $add);
        header("location:" . $main_website_url . "/../../admin_manager.php?successadded");
    } else {
        header("location:" . $main_website_url . "/../../admin_manager.php?wasfound");
    }
}
//end add
//start disable
elseif (isset($_POST['disableadmin']) and !empty($_POST['keshvariadmincode'])) {
    $operation = "disableadmin";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['keshvariadmincode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=1");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=0,deactivator='$editor',date_deactivated='$date' where `code`='$ratercode'");
        header("location:" . $main_website_url . "/../../admin_manager.php?disabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../admin_manager.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../admin_manager.php?notfound");
    }
}
//end disable
//start enable
elseif (isset($_POST['enableadmin']) and !empty($_POST['keshvariadmincode'])) {
    $operation = "enableadmin";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['keshvariadmincode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=1");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=1,deactivator='$editor',date_deactivated='$date' where `code`='$ratercode'");
        header("location:" . $main_website_url . "/../../admin_manager.php?enabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../admin_manager.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../admin_manager.php?notfound");
    }
}
//end enable
//end country admins
//start state admins
//start add
elseif (isset($_POST['setadminostani']) and !empty($_POST['username'])) {
    $operation = "setadminostani";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $nameadmin = $_POST['name'];
    $familyadmin = $_POST['family'];
    $useradmin = $_POST['username'];
    $phone = $_POST['phone'];
    $state = $_POST['state_custom'];
    $password = $_POST['password'];
    $inputuser = $_SESSION['username'];
    $gender = $_POST['gender'];
    $subject = $_POST['subject'];
    $activation_status = $_POST['activation_status'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$useradmin'");
    foreach ($result as $results) {
    }
    if (empty($results['username'])) {
        $add = "insert into `rater_list` (`name`,`family`,`code`,`phone`,`username`,`password`,`gender`,`input_user`,`type`,`subject`,approved,date_added,codemelli,city_name)
                            values ('$nameadmin','$familyadmin','$useradmin','$phone','$useradmin','$password','$gender','$inputuser',2,'$subject','$activation_status','$date','$useradmin','$state')";
        mysqli_query($connection, $add);
        header("location:".$main_website_url ."/../../ostani_admins.php?successadded");
    } else {
        header("location:" . $main_website_url . "/../../ostani_admins.php?wasfound");
    }
}
//end add
//start edit
elseif (isset($_POST['editadminostani']) and !empty($_POST['username'])) {
    $operation = "editadminostani";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $username = $_POST['username'];
    $nameadmin = $_POST['name'];
    $familyadmin = $_POST['family'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $state = $_POST['state_custom'];
    $user_status = $_POST['user_status'];
    $activation_status = $_POST['activation_status'];
    switch ($user_status) {
        case 0:
            $subject = 'ارزیاب جشنواره';
            break;
        case 2:
            $subject = 'دبیر جشنواره استان';
            break;
        case 3:
            $subject = 'دبیر مدرسه ای جشنواره';
            break;
    }
    $editor = $_SESSION['username'];
    $edit = "update rater_list set name='$nameadmin',family='$familyadmin',gender='$gender',phone='$phone',
                        city_name='$state',type='$user_status',subject='$subject',
                        approved='$activation_status',editor='$editor',date_edited='$datewithtime' where username='$username'";
    mysqli_query($connection, $edit);
    header("location:" . $main_website_url . "/../../ostani_admins.php?successedited&username=$username");
}
//end edit
//start deactive
elseif (isset($_POST['disableadminostani']) and !empty($_POST['disablecode'])) {
    $operation = "disableadminostani";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['disablecode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=2");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=0,deactivator='$editor',date_deactivated='$date' where code='$ratercode'");
        header("location:" . $main_website_url . "/../../ostani_admins.php?successdisabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../ostani_admins.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../ostani_admins.php?notfound");
    }
}
//end deactive
//start active
elseif (isset($_POST['enableadminostani']) and !empty($_POST['disablecode'])) {
    $operation = "enableadminostani";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['disablecode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=2");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=1,deactivator='$editor',date_deactivated='$date' where code='$ratercode'");
        mysqli_query($connection, "update rater_list set approved=1,deactivator='$editor',date_deactivated='$date' where code='$ratercode'");
        header("location:" . $main_website_url . "/../../ostani_admins.php?successenabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../ostani_admins.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../ostani_admins.php?notfound");
    }
}
//end active
//end state admins
//start school admins
//start add
elseif (isset($_POST['setadminmadrese']) and !empty($_POST['username'])) {
    $operation = "setadminmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeadmin = $_POST['username'];
    $nameadmin = $_POST['name'];
    $familyadmin = $_POST['family'];
    $useradmin = $_POST['username'];
    $phone = $_POST['phone'];
    $state = $_POST['state_custom'];
    $shahr = $_POST['city_custom'];
    $school = $_POST['school_custom'];
    $password = $_POST['password'];
    $inputuser = $_SESSION['username'];
    $gender = $_POST['gender'];
    $subject = $_POST['subject'];
    $ratercode = $_POST['username'];
    $subject = 'دبیر مدرسه ای جشنواره';
    $activation_status = $_POST['activation_status'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode'");
    foreach ($result as $results) {
    }
    if (empty($results['username'])) {
        $add = "insert into `rater_list` (`name`,`family`,`code`,`gender`,`phone`,`username`,`password`,`input_user`,`type`,`subject`,approved,date_added,codemelli,shahr_name,school_name,city_name)
                                                    values ('$nameadmin','$familyadmin','$ratercode','$gender','$phone','$ratercode','$password','$inputuser',3,'$subject','$activation_status','$date','$ratercode','$shahr','$school','$state')";
        if (mysqli_query($connection, $add)) {
            header("location:" . $main_website_url . "/../../madrese_admins.php?successadded&codemelli=$ratercode");
        }
    } else {
        header("location:" . $main_website_url . "/../../madrese_admins.php?wasfound");
    }
}
//end add
//start edit
elseif (isset($_POST['editadminmadrese']) and !empty($_POST['username'])) {
    $operation = "editadminmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $username = $_POST['username'];
    $nameadmin = $_POST['name'];
    $familyadmin = $_POST['family'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $state = $_POST['state_custom'];
    $city = $_POST['city_custom'];
    $school = $_POST['school_custom'];
    $user_status = $_POST['user_status'];
    $activation_status = $_POST['activation_status'];
    switch ($user_status) {
        case 0:
            $subject = 'ارزیاب جشنواره';
            break;
        case 2:
            $subject = 'دبیر جشنواره استان';
            break;
        case 3:
            $subject = 'دبیر مدرسه ای جشنواره';
            break;
    }
    $editor = $_SESSION['username'];
    $edit = "update rater_list set name='$nameadmin',family='$familyadmin',gender='$gender',phone='$phone',
                        city_name='$state',shahr_name='$city',school_name='$school',type='$user_status',
                        approved='$activation_status',subject='$subject',editor='$editor',date_edited='$datewithtime' where username='$username'";
    mysqli_query($connection, $edit);
    header("location:" . $main_website_url . "/../../madrese_admins.php?successedited&username=$username");
}
//end edit
//start deactive
elseif (isset($_POST['disableadminmadrese']) and !empty($_POST['disablecode'])) {
    $operation = "disableadminmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['disablecode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=3");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=0,deactivator='$editor',date_deactivated='$date' where code='$ratercode'");
        header("location:" . $main_website_url . "/../../madrese_admins.php?successdisabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../madrese_admins.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../madrese_admins.php?notfound");
    }
}
//end deactive
//start active
elseif (isset($_POST['enableadminmadrese']) and !empty($_POST['disablecode'])) {
    $operation = "enableadminmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $editor = $_POST['codeeditor'];
    $ratercode = $_POST['disablecode'];
    $result = mysqli_query($connection, "select * from rater_list where `code`='$ratercode' and `type`=3");
    foreach ($result as $results) {
    }
    if (!empty($results['username'])) {
        mysqli_query($connection, "update rater_list set approved=1,deactivator='$editor',date_deactivated='$date' where code='$ratercode'");
        header("location:" . $main_website_url . "/../../madrese_admins.php?successdisabled");
    } elseif (empty($ratercode)) {
        header("location:" . $main_website_url . "/../../madrese_admins.php?nullcode");
    } else {
        header("location:" . $main_website_url . "/../../madrese_admins.php?notfound");
    }
}
//end active
//end school admins
//end admin manager


//start set ejmali keshvari
elseif (isset($_POST['setejmali']) && isset($_POST['codeasarfield'])) {
    $operation = "setejmali";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $query=mysqli_query($connection,"select * from t_a_ejmali where codeasar='$codeasar'");
    foreach($query as $check){}
    if(!$check){
        mysqli_query($connection, "insert into t_a_ejmali (codeasar) values ('$codeasar')");
    }
    $comment = $_POST['nazar'];
    switch ($comment) {
        case 'راه‌یابی اثر به مرحله تفصیلی':
            $t1 = 13;
            $t2 = 4;
            $t3 = 4;
            $t4 = 8;
            $t5 = 8;
            $t6 = 25;
            $t7 = 10;
            $t8 = 4;
            $t9 = 4;
            $jamnomre = 80;
            break;
        case 'توقف اثر در مرحله اجمالی':
            $t1 = 12;
            $t2 = 3;
            $t3 = 3;
            $t4 = 6;
            $t5 = 7;
            $t6 = 22;
            $t7 = 10;
            $t8 = 3;
            $t9 = 3;
            $jamnomre = 69;
            break;
    }
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['coderater'];
    $query = "update `t_a_ejmali` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                        tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                        parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                        tarikhsabt_day='$day',tarikhsabt_month='$month',tarikhsabt_year='$year',
                        secsabt='$sec',minsabt='$min',hoursabt='$hour',rater_id='$user'
                        where codeasar='$codeasar' ";
    mysqli_query($connection, $query);
    mysqli_query($connection, "update `etelaat_a` set `tarikharzyabi`='$date',codearzyabejmali='$user' where `codeasar`='$codeasar'");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "insert into `tafsili2` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi='تفصیلی دوم',vaziatkarname='در حال ارزیابی' WHERE codeasar='$codeasar'");
    } else if ($jamnomre < 75) {
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi='اجمالی ردی',vaziatkarname='اتمام ارزیابی' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "ejmali_list.php?ejmaliregistrated");
}
//end set ejmali keshvari

//start ejmali ostani rater
elseif (isset($_POST['setejmaliostani']) && !empty($_POST['codeasarfield'])) {
    $operation = "setejmaliostani";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "insert into ejmali_ostan(codeasar) values ('$codeasar')");
    mysqli_query($connection, "update `ejmali_ostan` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                        tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                        parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                        tarikhsabt_day='$day',tarikhsabt_month='$month',tarikhsabt_year='$year',
                        secsabt='$sec',minsabt='$min',hoursabt='$hour',rater_id='$user'
                        where `codeasar`='$codeasar' ");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_ostan` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='تفصیلی اول' WHERE codeasar='$codeasar'");

    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='اجمالی ردی',vaziatkarnameostani='اتمام ارزیابی' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "panel.php?ejmaliregistrated");
}
//end ejmali ostan rater

//start ejmali madrese rater
elseif (isset($_POST['setejmalimadrese']) && !empty($_POST['codeasarfield'])) {
    $operation = "setejmalimadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "insert into ejmali_madrese(codeasar) values ('$codeasar')");
    mysqli_query($connection, "update `ejmali_madrese` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                        tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                        parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                        tarikhsabt_day='$day',tarikhsabt_month='$month',tarikhsabt_year='$year',
                        secsabt='$sec',minsabt='$min',hoursabt='$hour',rater_id='$user'
                        where `codeasar`='$codeasar' ");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_madrese` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='تفصیلی اول',vaziatkarnamemadrese='در حال ارزیابی',codearzyabejmali_madrese='$user' WHERE codeasar='$codeasar'");
    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "update etelaat_a set vaziatmadreseasar=null,nobat_arzyabi_madrese='اجمالی ردی',vaziatkarnamemadrese='اتمام ارزیابی',vaziatkarnameostani='اتمام ارزیابی',bargozideh_madrese='نمی باشد',codearzyabejmali_madrese='$user' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "panel.php?ejmaliregistrated");
}
//end ejmali madrese rater

//start ejmali ostani rater
elseif (isset($_POST['setejmalidabirostan']) && !empty($_POST['codeasarfield'])) {
    $operation = "setejmalidabirostan";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");
//	echo '<pre>';
//	print_r($_POST);
//	echo '</pre>';
    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "insert into ejmali_ostan(codeasar) values ('$codeasar')");
    mysqli_query($connection, "update `ejmali_ostan` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                    tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                    parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                    tarikhsabt_day='$day',tarikhsabt_month='$month',tarikhsabt_year='$year',
                    secsabt='$sec',minsabt='$min',hoursabt='$hour',rater_id='$user'
                    where `codeasar`='$codeasar' ");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_ostan` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='تفصیلی اول',codearzyabejmali_ostani='$user' WHERE codeasar='$codeasar'");

    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='اجمالی ردی',vaziatkarnameostani='اتمام ارزیابی',codearzyabejmali_ostani='$user' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "ejmali_list.php?ejmaliregistrated");
}
//end ejmali ostan rater

//start ejmali madrese rater
elseif (isset($_POST['setejmalidabirmadrese']) && !empty($_POST['codeasarfield'])) {
    $operation = "setejmalidabirmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "insert into ejmali_madrese(codeasar) values ('$codeasar')");
    mysqli_query($connection, "update `ejmali_madrese` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                    tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                    parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                    tarikhsabt_day='$day',tarikhsabt_month='$month',tarikhsabt_year='$year',
                    secsabt='$sec',minsabt='$min',hoursabt='$hour',rater_id='$user'
                    where `codeasar`='$codeasar' ");
    mysqli_query($connection, "update etelaat_a set codearzyabejmali_madrese='$user' where codeasar='$codeasar'");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_madrese` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='تفصیلی اول',vaziatkarnamemadrese='در حال ارزیابی' WHERE codeasar='$codeasar'");
    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='اجمالی ردی',vaziatkarnamemadrese='اتمام ارزیابی' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "ejmali_list.php?ejmaliregistrated");
}
//end ejmali madrese rater

//start edit ejmali ostani
elseif (isset($_POST['editeo']) && !empty($_POST['codeasarfield'])) {
    $operation = "editejmalidabirostan";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "update `ejmali_ostan` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                    tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                    parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                    edit_date='$datewithtime',editor='$user'
                    where `codeasar`='$codeasar' ");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_ostan` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='تفصیلی اول',vaziatkarnameostani='در حال ارزیابی' WHERE codeasar='$codeasar'");

    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "delete from tafsili1_ostan where codeasar='$codeasar'");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_ostani='اجمالی ردی',vaziatkarnameostani='اتمام ارزیابی' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "ejmali_edit.php?ejmaliregistrated");
}
//end edit ejmali ostani

//start edit ejmali madrese
elseif (isset($_POST['editem']) && !empty($_POST['codeasarfield'])) {
    $operation = "editejmalidabirmadrese";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codeasar = $_POST['codeasarfield'];
    $t1 = $_POST['t1'];
    $t2 = $_POST['t2'];
    $t3 = $_POST['t3'];
    $t4 = $_POST['t4'];
    $t5 = $_POST['t5'];
    $t6 = $_POST['t6'];
    $t7 = $_POST['t7'];
    $t8 = $_POST['t8'];
    $t9 = $_POST['t9'];
    $tozih = $_POST['tozihat'];
    $user = $_SESSION['username'];
    $jamnomre = $t1 + $t2 + $t3 + $t4 + $t5 + $t6 + $t7 + $t8 + $t9;
    mysqli_query($connection, "update `ejmali_madrese` set reayatsakhtarasar='$t1',shivaeematn='$t2',reayataeinnegaresh='$t3',
                    tabiinmasale='$t4',manabemotabar='$t5',ghabeliatelmiasar='$t6',sazmandehimabahes='$t7',
                    parhizazmatalebzaed='$t8',keyfiatjambandi='$t9',tozihat='$tozih',jam='$jamnomre',
                    edit_date='$datewithtime',editor='$user'
                    where `codeasar`='$codeasar' ");
    if ($jamnomre >= 75) {
        mysqli_query($connection, "insert into `tafsili1_madrese` (`codeasar`) values ('$codeasar')");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='تفصیلی اول',vaziatkarnamemadrese='در حال ارزیابی' WHERE codeasar='$codeasar'");
    } elseif ($jamnomre < 75) {
        mysqli_query($connection, "delete from tafsili1_madrese where codeasar='$codeasar'");
        mysqli_query($connection, "update etelaat_a set nobat_arzyabi_madrese='اجمالی ردی',vaziatkarnamemadrese='اتمام ارزیابی' WHERE codeasar='$codeasar'");
    }
    header("location:" . $main_website_url . "ejmali_edit.php?ejmaliregistrated");
}
//end edit ejmali madrese

//start add non keshvari rater
elseif (isset($_POST['subsetnonkeshvarirater']) and !empty($_POST['code'])) {
    $operation = "subsetnonkeshvarirater";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    echo $_POST['code'];
    $codearzyab = $_POST['code'];
    $query = mysqli_query($connection, "select * from rater_list where code='$codearzyab'");
    foreach ($query as $checkraterfound) {
    }
    if ($checkraterfound != null) {
        header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?raterfounded&code=$codearzyab");
    } else {
        $password = $_POST['password'];
        $namearzyab = $_POST['name'];
        $familyarzyab = $_POST['family'];
        if ($_POST['arshad'] == 'on') {
            $arshad = 'کارشناسی ارشد';
        }
        if ($_POST['doctor'] == 'on') {
            $doctor = 'دکتری';
        }
        if ($_POST['sath3'] == 'on') {
            $sath3 = 'سطح 3 حوزه';
        }
        if ($_POST['sath4'] == 'on') {
            $sath4 = 'سطح 4 حوزه';
        }
        $sathelmiarzyab = $arshad . '-' . $doctor . '-' . $sath3 . '-' . $sath4;
//			$codemelli=$_POST['codemelli'];
        $gender = $_POST['gender'];

//                            $adabiat=$_POST['adabiat'];
//                            $akhlaghtarbiat=$_POST['akhlaghtarbiat'];
//                            $hadisderaye=$_POST['hadisderaye'];
//                            $falsafe=$_POST['falsafe'];
//                            $tafsir=$_POST['tafsir'];
//                            $kalaam=$_POST['kalaam'];
//                            $ulumensani=$_POST['ulumensani'];
//                            $feghh=$_POST['feghh'];
//                            $osoolfegh=$_POST['osoolfegh'];
//                            $tarikheslam=$_POST['tarikheslam'];
//                            $tashihtaligh=$_POST['tashihtaligh'];
//                            $tarjome=$_POST['tarjome'];

        $arzyabsamane = 'ارزیاب جشنواره';
        $phone = $_POST['phone'];
        $state = $_POST['state_custom'];
        $city = $_POST['city_custom'];
        $school = $_POST['school'];
        if ($city == '') {
            $city = NULL;
        }
        if ($school == '') {
            $school = NULL;
        }

        $inputuser = $_POST['user'];

//                            if ($adabiat==null){
//                                $adabiat= NULL;
//                            }
//                            if ($akhlaghtarbiat==null){
//                                $akhlaghtarbiat= NULL;
//                            }
//                            if ($hadisderaye==null){
//                                $hadisderaye= NULL;
//                            }
//                            if ($falsafe==null){
//                                $falsafe= NULL;
//                            }
//                            if ($tafsir==null){
//                                $tafsir= NULL;
//                            }
//                            if ($kalaam==null){
//                                $kalaam= NULL;
//                            }
//                            if ($ulumensani==null){
//                                $ulumensani= NULL;
//                            }
//                            if ($feghh==null){
//                                $feghh= NULL;
//                            }
//                            if ($osoolfegh==null){
//                                $osoolfegh= NULL;
//                            }
//                            if ($tarikheslam==null){
//                                $tarikheslam= NULL;
//                            }
        if ($sathelmiarzyab == "") {
            $sathelmiarzyab = NULL;
        }
//                            if ($tashihtaligh==null){
//                                $tashihtaligh=NULL;
//                            }
//                            if ($tarjome==null){
//                                $tarjome=NULL;
//                            }
        $date = $year . "/" . $month . "/" . $day;
        $addtorater_list = "insert into `rater_list` (`name`,`family`,`code`,`gender`,`codemelli`,`sath_elmi`,`phone`,`username`,`password`,`city_name`,`shahr_name`,`school_name`,`input_user`,`approved`,`subject`,`type`,`date_added`)
                                                values ('$namearzyab','$familyarzyab','$codearzyab','$gender','$codearzyab','$sathelmiarzyab','$phone','$codearzyab','$password','$state','$city','$school','$inputuser',0,'$arzyabsamane',0,'$date')";
        mysqli_query($connection, $addtorater_list);
        $query = mysqli_query($connection, "select * from school_list where name='$school' and state='$state' and city='$city'");
        foreach ($query as $schoollist) {
        }
        if ($schoollist == null and $school != null) {
            $addtoschool_list = "insert into school_list (name,state,city,registrant_user,date_added) values ('$school','$state','$city','$inputuser','$date')";
            mysqli_query($connection, $addtoschool_list);
        }
        $info = $namearzyab . ' ' . $familyarzyab;
        header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?addedrater&code=$codearzyab&info=$info");
    }
}
//end add non keshvari rater

//start edit non keshvari rater
elseif (isset($_POST['subeditnonkeshvarirater']) and !empty($_POST['editratercode'])) {
    $operation = "subeditnonkeshvarirater";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $codearzyab = $_POST['editratercode'];
    $query = mysqli_query($connection, "select * from rater_list where code='$codearzyab'");
    foreach ($query as $checkraterfound) {
    }
    if ($checkraterfound == null) {
        header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?raternotfounded&code=$codearzyab");
    } else {
        $namearzyab = $_POST['name'];
        $familyarzyab = $_POST['family'];
        $password=$_POST['password'];
        if ($_POST['arshad'] == 'on') {
            $arshad = 'کارشناسی ارشد';
        }
        if ($_POST['doctor'] == 'on') {
            $doctor = 'دکتری';
        }
        if ($_POST['sath3'] == 'on') {
            $sath3 = 'سطح 3 حوزه';
        }
        if ($_POST['sath4'] == 'on') {
            $sath4 = 'سطح 4 حوزه';
        }
        $sathelmiarzyab = $arshad . '-' . $doctor . '-' . $sath3 . '-' . $sath4;
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $state = $_POST['state_custom'];
        $city = $_POST['city_custom'];
        $school = $_POST['school'];
        if ($city == '') {
            $city = NULL;
        }
        if ($school == '') {
            $school = NULL;
        }
        $inputuser = $_POST['user'];
        if ($sathelmiarzyab == "") {
            $sathelmiarzyab = NULL;
        }
        $user_status = $_POST['user_status'];
        $active_status = $_POST['active_status'];
        switch ($user_status) {
            case 2:
                $subject = 'دبیر جشنواره استان';
                break;
            case 3:
                $subject = 'دبیر مدرسه ای جشنواره';
                break;
            default:
                $subject = 'ارزیاب جشنواره';
                break;
        }
        $date = $year . "/" . $month . "/" . $day;
        $editraterostani = "update rater_list set name='$namearzyab',family='$familyarzyab',password='$password',sath_elmi='$sathelmiarzyab',phone='$phone',gender='$gender',city_name='$state',shahr_name='$city',school_name='$school',type='$user_status',subject='$subject',approved='$active_status',editor='$user',date_edited='$date' where username='$codearzyab'";
        mysqli_query($connection, $editraterostani);
        $info = $namearzyab . ' ' . $familyarzyab;
        header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?editedrater&code=$codearzyab&info=$info");
    }
}
//end edit non keshvari rater

//start approve raters
elseif (isset($_POST['set_approved']) and !empty($_POST['city_name'])) {
    $operation = "set_approved";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    $city = $_POST['city_name'];
    $user = $_SESSION['username'];
    mysqli_query($connection, "update rater_list set approved=1,editor='$user',date_edited='$date' where city_name='$city' and approved=2");
    header("location:" . $main_website_url . "/../../panel.php?successapproved&city=$city");
}
//end approve raters

//start redirect
elseif (isset($_POST['makenewpage']) and !empty($_POST['pageaddress'])) {
    $address = $_POST['pageaddress'];
    $operation = "redirectpageaddress($address)";
    mysqli_query($connection, "insert into link_logs (id,url,operation,time,username) values ('$LinkLogID','$urlofthispage','$operation','$dateforupdateloghelli','$user')");

    header("location:" . $main_website_url . "../../$address");
}
//end redirect

//start disable ostani rater
elseif (!empty($_POST['disableostanirater']) and isset($_POST['ratercode'])) {
    $ratercode = $_POST['ratercode'];
    mysqli_query($connection, "update rater_list set approved=0 where code='$ratercode'");
    header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?disabled&code=$ratercode");
}
//end disable ostani rater

//start enable ostani rater
elseif (!empty($_POST['enableostanirater']) and isset($_POST['ratercode'])) {
    $ratercode = $_POST['ratercode'];
    mysqli_query($connection, "update rater_list set approved=1 where code='$ratercode'");
    header("location:" . $main_website_url . "/../../rater_pages/add_rater_ostani.php?enabled&code=$ratercode");
}
//end enable ostani rater

//start remove ostani admin
elseif (!empty($_POST['removeostaniadmin']) and isset($_POST['disablecode'])) {
    $ratercode = $_POST['disablecode'];
    mysqli_query($connection, "update rater_list set approved=0,type=8 where code='$ratercode'");
    header("location:" . $main_website_url . "/../../ostani_admins.php?removed&code=$ratercode");
}
//end remove ostani admin

//start remove ostani admin
elseif (!empty($_POST['removemadreseadmin']) and isset($_POST['disablecode'])) {
    $ratercode = $_POST['disablecode'];
    mysqli_query($connection, "update rater_list set approved=0,type=8 where code='$ratercode'");
    header("location:" . $main_website_url . "/../../madrese_admins.php?removed&code=$ratercode");
} //end remove ostani admin

else {
    header("location:" . $main_website_url . "panel.php?wronglink");
}