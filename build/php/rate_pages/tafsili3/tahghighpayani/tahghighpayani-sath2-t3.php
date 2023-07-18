<form method="post" action="inc_t3.php" id="myform" onsubmit="return validateForm()">
    <div class="box-body">
        <table style="font-size: 15px">
            <tr style="border-bottom: 3px solid black; ">
                <td style="padding-bottom: 5px">
                    <label>
                        کد اثر:
                        <?php echo $codeasar; ?>
                    </label>
                </td>
            </tr>
            <?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])): ?>
        <tr style="border-bottom: 3px solid black; ">
        <?php else: ?>
            <tr>
                <?php endif; ?>
                <td style="padding-top: 8px">
                    <label>
                        نام اثر:
                        <?php echo $item['nameasar']; ?>
                    </label>
                </td>
            </tr>
            <?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])): ?>
                <tr>
                    <td style="padding-top: 8px">
                        <label>
                            ارزیابی این اثر توسط استاد
                            <?php
                            $rater_id=$item['rater_id'];
                            $query=mysqli_query($connection,"select * from rater_list where username='$rater_id'");
                            foreach ($query as $rater_items){}
                            echo $rater_items['name'].' '.$rater_items['family'];
                            ?>
                            در تاریخ
                            <?php echo $item['datesabt'] ?>
                            ثبت شده است
                        </label>
                    </td>
                </tr>
            <?php endif; ?>
        </table>

        <br/>
        <center>
            <table class="tabletafsili" >
                <tr>
                    <th>رعایت ساختار اثر:
                        <br>
                        <span style="color: darkgreen; font-size: 11px">
                        [عنوان مناسب (1) - چکیده (3) - کلیدواژه (1) - مقدمه (3) - فهرست منابع (2) - ارجاع‌دهی واضح و شفاف (2) - نتیجه‌گیری (3) - رعایت ضوابط پایان نامه نویسی (5)]</th>
                    </th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t1" type="number" step="any" name="t1" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['reayatsakhtarasar'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['reayatsakhtarasar'];} ?>">
                        &nbsp;&nbsp;
                        از 20
                    </td>

                </tr>
                <tr>
                    <th>شیوایی و رسایی متن</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t2" type="number" step="any" name="t2" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['shivaeematn'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['shivaeematn'];} ?>">

                        &nbsp;&nbsp;
                        از 6
                    </td>
                </tr>
                <tr>
                    <th>رعایت آیین نگارش </th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t3" type="number" step="any" name="t3" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['reayataeinnegaresh'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['reayataeinnegaresh'];} ?>">
                        &nbsp;&nbsp;
                        از 7
                    </td>
                </tr>
                <tr>
                    <th>تبیین و تحدید دقیق مسأله</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t4" type="number" step="any" name="t4" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['tabiinmasale'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['tabiinmasale'];} ?>">
                        &nbsp;&nbsp;
                        از 6
                    </td>
                </tr>
                <tr>
                    <th>سازماندهی و ترتیب منطقی مباحث</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t5" type="number" step="any" name="t5" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['sazmandehimabahes'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['sazmandehimabahes'];} ?>">
                        &nbsp;&nbsp;
                        از 10
                    </td>
                </tr>
                <tr>
                    <th>پرهیز از مطالب حاشیه‌ای و زائد</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t6" type="number" step="any" name="t6" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['parhizazmatalebzaed'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['parhizazmatalebzaed'];} ?>">
                        &nbsp;&nbsp;
                        از 3
                    </td>
                </tr>
                <tr>
                    <th>استفاده از منابع معتبر و متعدد</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t7" type="number" step="any" name="t7" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['estefadeazmanabe'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['estefadeazmanabe'];} ?>">

                        &nbsp;&nbsp;
                        از 6
                    </td>
                </tr>
                <tr>
                    <th>کیفیت تببین و تحلیل مطالب و صحت استدلال</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t8" type="number" step="any" name="t8" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['keyfiattabiinmataleb'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['keyfiattabiinmataleb'];} ?>">
                        &nbsp;&nbsp;
                        از 20</td>
                </tr>
                <tr>
                    <th>اهمیت مسأله و ابتناء آن بر نیاز</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t9" type="number" step="any" name="t9" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['ahammiatmasale'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['ahammiatmasale'];} ?>">
                        &nbsp;&nbsp;
                        از 3
                    </td>
                </tr>
                <tr>
                    <th>نوآوری در تنظیم و ارائه مطالب</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t10" type="number" step="any" name="t10" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['noavaridartanzim'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['noavaridartanzim'];} ?>">
                        &nbsp;&nbsp;
                        از 4
                    </td>
                </tr>
                <tr>
                    <th>روشمندی اثر (توصیفی، تبیینی، تطبیقی، تحلیلی و...)</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t11" type="number" step="any" name="t11" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['raveshmandiasar'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['raveshmandiasar'];} ?>">
                        &nbsp;&nbsp;
                        از 5
                    </td>
                </tr>
                <tr>
                    <th>پردازش محتوا بوسیله نویسنده</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t12" type="number" step="any" name="t12" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['pardazeshmohtava'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['pardazeshmohtava'];} ?>">
                        &nbsp;&nbsp;
                        از 6
                    </td>
                </tr>
                <tr>
                    <th>کیفیت استنتاج و دست‌یابی به اهداف پژوهش</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t13" type="number" step="any" name="t13" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['dastyabibeahdaf'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['dastyabibeahdaf'];} ?>">
                        &nbsp;&nbsp;
                        از 4
                    </td>
                </tr>
                <tr>
                    <th>نقد و نوآوری علمی</th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t14" type="number" step="any" step="any" name="t14" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['naghdvanoavarielmi'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['naghdvanoavarielmi'];} else {echo 0;} ?>">
                        &nbsp;&nbsp;
                        3+
                    </td>
                </tr>
                <tr>
                    <th>
                        نو بودن مسأله یا موضوع
                    </th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t15" type="number" step="any" name="t15" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['noboodanmozoo'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['noboodanmozoo'];} else {echo 0;} ?>">
                        &nbsp;&nbsp;
                        2+
                    </td>
                </tr>
                <tr>
                    <th>عدم رعایت اخلاق پژوهشی:
                        <br>
                        <span style="color: darkgreen; font-size: 11px">
                            [ادب در نقد، تواضع علمی، احترام به محققین و مؤلفین و...]
                        </span>

                    </th>
                    <td>
                        <input <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> id="t16" type="number" step="any" name="t16" class="rate_inputs" onblur="calcular()" value="<?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo $tafsili3items['adamreayatakhlaghpazhooheshi'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m'){echo @$item['adamreayatakhlaghpazhooheshi'];} else {echo 0;} ?>">
                        &nbsp;&nbsp;
                        5-
                    </td>
                </tr>
                <tr >
                    <th style="border: 0px;border-bottom-color: white;text-align: left; background-color: white">

                    </th>
                    <td style="border: 0px;text-align: center; ">
                        جمع امتیازات:
                        <label style="padding: 1px;color: blue" id="resultado"><?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3'){echo $tafsili3items['jam'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo @$item['jam'];} ?></label>
                    </td>
                </tr>
            </table>

            <br/>
            <div class="row">
                <section class="col-lg-12 col-md-12">
                    <div class="box box-solid box-warning">
                        <div class="box-header">
                            <h3 class="box-title">
                                ارزشیابی توصیفی
                            </h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <textarea <?php if($_POST['subjection']=='log'){echo 'disabled';} ?> name="tozihat" id="tosifi" class="tozihat_textarea"  placeholder="ارزیاب محترم: با توجه به ارسال نقاط قوت و ضعف این اثر برای نویسنده، لطفا ارزشیابی توصیفی خود درباره این اثر و موارد احتمالی قوت یا ضعف آن را بصورت جزئی و حداقل در 250 کاراکتر در این کادر یادداشت فرمایید."><?php if($_POST['subjection']=='log' or $_POST['subjection']=='editt3'){echo $tafsili3items['tozihat'];} ?><?php if ($_POST['subjection']=='editt3o' or $_POST['subjection']=='editt3m' or isset($_POST['tafsili3_ostan_log']) or isset($_POST['tafsili3_madrese_log'])){echo @$item['tozihat'];} ?></textarea>

                    </div>
                </section>
            </div>
            <br/>
            <center>
                <input type="hidden" name="codeasarfield" value="<?php echo $codeasar; ?>">
	            <?php if ($_POST['subjection']==null or $_POST['subjection']=='subset'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="subt3tahghighpayani" value="ثبت ارزیابی">
                    </p>
	            <?php elseif ($_POST['subjection']=='statet3'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="subt3tahghighpayaniostani" value="ثبت ارزیابی">
                    </p>
	            <?php elseif($_POST['subjection']=='schoolt3'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="subt3tahghighpayanimadrese" value="ثبت ارزیابی">
                    </p>
	            <?php elseif($_POST['subjection']=='editt3'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="editt3tahghighpayani" value="ثبت ارزیابی">
                    </p>
	            <?php elseif ($_POST['subjection']=='tafsili3ostan'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="subt3tahghighpayaniostani" value="ثبت ارزیابی">
                    </p>
	            <?php elseif ($_POST['subjection']=='tafsili3madrese'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input onclick="return submitconfirm()" style="padding: 6px" type="submit" name="subt3tahghighpayanimadrese" value="ثبت ارزیابی">
                    </p>
                <?php elseif ($_POST['subjection']=='editt3o'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input  onclick="return submitconfirm()" style="padding: 6px" type="submit" name="editt3tahghighpayaniostani" value="ویرایش ارزیابی">
                    </p>
                <?php elseif ($_POST['subjection']=='editt3m'): ?>
                    <p class="virayesh-ejmali-button" >
                        <input  onclick="return submitconfirm()" style="padding: 6px" type="submit" name="editt3tahghighpayanimadrese" value="ویرایش ارزیابی">
                    </p>
	            <?php endif; ?>
            </center>

</form>
</center>



</div>
</form>
<script src="build/js/rateforms/t3/t2.js"></script>