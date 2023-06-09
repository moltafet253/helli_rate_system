<?php
include_once __DIR__ . '/header.php';
if ($_SESSION['head'] == 1 and $_SESSION['full_access'] == 1):
    ?>
    <div class="content-wrapper">
    <div class="row">
        <section class="col-lg-12 col-md-12">
            <div class="box box-danger">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">
                        این نکات خوانده شود:
                    </h3>
                </div>
                <div class="box-body">
                    <p>لطفا پس از اتمام کار با سامانه، از حساب کاربری خود خارج شوید.</p>
                    <p>لطفا در حفظ و نگهداری نام کاربری و رمز عبور خود نهایت دقت را داشته باشید.</p>
                </div>
            </div>
        </section>
    </div>
    <!-- Content Wrapper. Contains page content -->
    <div class="row">
        <section class="col-lg-12 col-md-12">
            <div class="box box-solid box-success">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">
                        تنظیمات کاربر ثبت نامی در سامانه ثبت نام
                    </h3>
                </div>
                <div class="box-body">
                    <form method="get" id="checkShow">
                        <input required type="text" name="national_code" class="form-control col-md-3"
                               id="national_code"
                               value="<?php if (isset($_GET['national_code'])) {
                                   echo $_GET['national_code'];
                               } ?>"
                               placeholder="لطفا کد ملی را وارد نمایید"/>
                        <button name="search_posts" type="submit" class="btn btn-primary" style="margin-right:10px">
                            جستجو
                        </button>
                    </form>
                </div>
                <?php
                if (isset($_GET['search_posts']) and !empty($_GET['national_code'])):
                $national_code = $_GET['national_code'];
                $query = mysqli_query($signup_connection, "select * from users WHERE national_code='$national_code' order by id desc");
                foreach ($query as $userInfo) {
                }
                if (!@$userInfo) {
                    ?>
                    <div class="callout callout-warning">
                        اثری با کد ملی وارد شده، در جشنواره جاری یافت نشد.
                    </div>
                    <?php
                } else {
                $query = mysqli_query($signup_connection, "select * from contacts WHERE national_code='$national_code' order by id desc");
                foreach ($query as $contactInfo) {
                }
                $query = mysqli_query($signup_connection, "select * from educational_infos WHERE national_code='$national_code' order by id desc");
                foreach ($query as $educationalInfo) {
                }
                $query = mysqli_query($signup_connection, "select * from teaching_infos WHERE national_code='$national_code' order by id desc");
                foreach ($query as $teachingInfo) {
                }
                ?>
                <input type="hidden" value="<?php echo $national_code; ?>" id="nationalcodeForInc">
            </div>

            <div class="box box-warning">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">
                        اطلاعات شخصی
                    </h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>نام پدر</th>
                            <th>کد ملی</th>
                            <th>شماره شناسنامه</th>
                            <th>تاریخ تولد</th>
                            <th>جنسیت</th>
                            <th>تاریخ اولین ورود</th>
                        </tr>
                        <tr>
                            <td><?php echo $userInfo['name']; ?></td>
                            <td><?php echo $userInfo['family']; ?></td>
                            <td><?php echo $userInfo['father_name']; ?></td>
                            <td><?php echo $userInfo['national_code']; ?></td>
                            <td><?php
                                if ($userInfo['shenasname'] == 0) {
                                    echo $userInfo['national_code'];
                                } else {
                                    echo $userInfo['shenasname'];
                                }
                                ?></td>
                            <td><?php echo $userInfo['birthdate']; ?></td>
                            <td><?php echo $userInfo['gender']; ?></td>
                            <td>
                                <?php
                                $sent_date = substr($userInfo['created_at'], 0, 10);
                                $dateParts = explode("-", $sent_date);
                                $year = $dateParts[0];
                                $month = $dateParts[1];
                                $day = $dateParts[2];
                                print_r(gregorian_to_jalali($year, $month, $day, '/'));
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="box box-danger">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">
                        اطلاعات تماس
                    </h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>تلفن ثابت (به همراه کد شهر)</th>
                            <th>تلفن همراه</th>
                            <th>کدپستی (بدون خط فاصله)</th>
                            <th>تاریخ ورود اطلاعات</th>
                            <th>وضعیت</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control text-left"
                                       id="phone"
                                       value="<?php if (isset($contactInfo['phone'])) {
                                           echo $contactInfo['phone'];
                                       } ?>"
                                       placeholder="شماره ثابت را وارد کنید"/>
                            </td>
                            <td>
                                <input type="text" name="mobile" class="form-control text-left"
                                       id="mobile"
                                       value="<?php if (isset($contactInfo['mobile'])) {
                                           echo $contactInfo['mobile'];
                                       } ?>"
                                       placeholder="شماره همراه را وارد کنید"/>
                            </td>
                            <td>
                                <input type="text" name="postal_code" class="form-control text-left"
                                       id="postal_code"
                                       value="<?php if (isset($contactInfo['postal_code'])) {
                                           echo $contactInfo['postal_code'];
                                       } ?>"
                                       placeholder="کدپستی را وارد کنید"/>
                            </td>
                            <td>
                                <?php
                                if ($contactInfo['updated_at'] != $contactInfo['created_at']) {
                                    $sent_date = substr($userInfo['updated_at'], 0, 10);
                                    $dateParts = explode("-", $sent_date);
                                    $year = $dateParts[0];
                                    $month = $dateParts[1];
                                    $day = $dateParts[2];
                                    print_r(gregorian_to_jalali($year, $month, $day, '/'));
                                } else {
                                    echo '';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($contactInfo['approved'] == 0) {
                                    ?>
                                    <button class="btn btn-danger" id="contactSaveTo1">ذخیره نشده</button>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn btn-success" id="contactSaveTo0">ذخیره شده</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>آدرس</th>
                            <td colspan="3">
                                <textarea class="form-control" rows="3" placeholder="آدرس را وارد کنید"
                                          id="address"><?php echo $contactInfo['address']; ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="box box-warning">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">
                        اطلاعات تحصیلی
                    </h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped text-center">
                        <tr>
                            <th>نام مرکز حوزوی</th>
                            <th>استان محل تحصیل</th>
                            <th>شهر محل تحصیل</th>
                            <th>مدرسه</th>
                            <th>تاریخ ثبت اطلاعات</th>
                            <th>وضعیت</th>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control select2"
                                        data-placeholder="نام مرکز حوزوی را انتخاب کنید"
                                        style="text-align: right"
                                        id="namemarkaztahsili">
                                    <option value="" selected disabled>انتخاب نشده</option>
                                    <?php
                                    $query=mysqli_query($signup_connection,"select distinct markaz from provinces order by markaz");
                                    foreach ($query as $markaz):
                                    ?>
                                        <option <?php if ($markaz['markaz']==$educationalInfo['namemarkaztahsili']) echo 'selected'; ?> value="<?php echo $markaz['markaz']; ?>"> <?php echo $markaz['markaz']; ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select2"
                                        data-placeholder="استان محل تحصیل را انتخاب کنید"
                                        style="text-align: right"
                                        id="ostantahsili">
                                    <option value="" selected disabled>انتخاب نشده</option>
                                    <?php
                                    if ($educationalInfo['namemarkaztahsili']){
                                        $namemarkaztahsili=$educationalInfo['namemarkaztahsili'];
                                        $query = mysqli_query($signup_connection, "select distinct ostan from provinces where markaz='$namemarkaztahsili' order by ostan");
                                    }else {
                                        $query = mysqli_query($signup_connection, "select distinct ostan from provinces order by ostan");
                                    }
                                    foreach ($query as $ostantahsili):
                                        ?>
                                        <option <?php if ($ostantahsili['ostan']==$educationalInfo['ostantahsili']) echo 'selected'; ?> value="<?php echo $ostantahsili['ostan']; ?>"> <?php echo $ostantahsili['ostan']; ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            <td>
                                <select class="form-control select2"
                                        data-placeholder="شهر محل تحصیل را انتخاب کنید"
                                        style="text-align: right"
                                        id="shahrtahsili">
                                    <option value="" selected disabled>انتخاب نشده</option>
                                    <?php
                                    if ($educationalInfo['ostantahsili']){
                                        $ostantahsili=$educationalInfo['ostantahsili'];
                                        $query = mysqli_query($signup_connection, "select distinct shahr from provinces where ostan='$ostantahsili' order by ostan");
                                    }else {
                                        $query = mysqli_query($signup_connection, "select distinct shahr from provinces order by shahr");
                                    }
                                    foreach ($query as $shahrtahsili):
                                        ?>
                                        <option <?php if ($shahrtahsili['shahr']==$educationalInfo['shahrtahsili']) echo 'selected'; ?> value="<?php echo $shahrtahsili['shahr']; ?>"> <?php echo $shahrtahsili['shahr']; ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select2"
                                        data-placeholder="مدرسه محل تحصیل را انتخاب کنید"
                                        style="text-align: right"
                                        id="madresetahsili">
                                    <option value="" selected disabled>انتخاب نشده</option>
                                    <?php
                                    if ($educationalInfo['madresetahsili']){
                                        $ostantahsili=$educationalInfo['ostantahsili'];
                                        $shahrtahsili=$educationalInfo['shahrtahsili'];
                                        $query = mysqli_query($signup_connection, "select distinct madrese from provinces where ostan='$ostantahsili' and shahr='$shahrtahsili' order by ostan");
                                    }else {
                                        $query = mysqli_query($signup_connection, "select distinct madrese from provinces order by shahr");
                                    }
                                    foreach ($query as $madresetahsili):
                                        ?>
                                        <option <?php if ($madresetahsili['madrese']==$educationalInfo['madresetahsili']) echo 'selected'; ?> value="<?php echo $madresetahsili['madrese']; ?>"> <?php echo $madresetahsili['madrese']; ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            <td>
                                <?php
                                if ($educationalInfo['updated_at'] != $educationalInfo['created_at']) {
                                    $sent_date = substr($educationalInfo['updated_at'], 0, 10);
                                    $dateParts = explode("-", $sent_date);
                                    $year = $dateParts[0];
                                    $month = $dateParts[1];
                                    $day = $dateParts[2];
                                    print_r(gregorian_to_jalali($year, $month, $day, '/'));
                                } else {
                                    echo '';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($educationalInfo['approved'] == 0) {
                                    ?>
                                    <button class="btn btn-danger" id="educationalSaveTo1">ذخیره نشده</button>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn btn-success" id="educationalSaveTo0">ذخیره شده</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>شماره شناسنامه</th>
                            <th>شماره پرونده حوزوی</th>
                            <th>مدرک تحصیلی دانشگاهی</th>
                            <th>رشته تحصیلی دانشگاهی</th>
                            <th>مرکز تخصصی حوزوی (در صورت تحصیل)</th>
                            <th>رشته تخصصی حوزوی</th>
                        </tr>

                    </table>
                </div>
            </div>
            <?php } endif;
            ?>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <script src="./build/js/SignupUsers.js"></script>
<?php
endif;
include_once __DIR__ . '/footer.php';
?>