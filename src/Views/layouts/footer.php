<?php $base = defined('BASE_URL') ? BASE_URL : ''; ?>

<footer class="site-footer" role="contentinfo">
    <!-- برگ‌های متحرک پس‌زمینه -->
    <div class="footer-leaves" aria-hidden="true">
        <div class="footer-leaf fl-1"></div>
        <div class="footer-leaf fl-2"></div>
        <div class="footer-leaf fl-3"></div>
    </div>

    <div class="footer-container">
        <div class="footer-grid">
            <!-- ستون لوگو و درباره -->
            <div class="footer-col footer-about">
                <a href="<?= $base ?>/" class="footer-logo">
                    <i class="fas fa-leaf" aria-hidden="true"></i>
                    Shop<span>Velora</span>
                </a>
                <p>فروشگاه تخصصی پوشاک و اکسسوری با طرح‌های پاییزی. بهترین کیفیت با قیمت مناسب.</p>
                <div class="footer-social">
                    <a href="#" aria-label="اینستاگرام"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                    <a href="#" aria-label="تلگرام"><i class="fab fa-telegram" aria-hidden="true"></i></a>
                    <a href="#" aria-label="واتساپ"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- ستون دسترسی سریع -->
            <div class="footer-col">
                <h3 class="footer-heading">دسترسی سریع</h3>
                <ul class="footer-links">
                    <li><a href="<?= $base ?>/"><i class="fas fa-chevron-left" aria-hidden="true"></i> صفحه اصلی</a></li>
                    <li><a href="<?= $base ?>/products"><i class="fas fa-chevron-left" aria-hidden="true"></i> محصولات</a></li>
                    <li><a href="<?= $base ?>/about"><i class="fas fa-chevron-left" aria-hidden="true"></i> درباره ما</a></li>
                    <li><a href="<?= $base ?>/login"><i class="fas fa-chevron-left" aria-hidden="true"></i> ورود به حساب</a></li>
                    <li><a href="<?= $base ?>/register"><i class="fas fa-chevron-left" aria-hidden="true"></i> ثبت‌نام</a></li>
                </ul>
            </div>

            <!-- ستون دسته‌بندی‌ها -->
            <div class="footer-col">
                <h3 class="footer-heading">دسته‌بندی‌ها</h3>
                <ul class="footer-links">
                    <li><a href="<?= $base ?>/products?cat=1"><i class="fas fa-chevron-left" aria-hidden="true"></i> پوشاک مردانه</a></li>
                    <li><a href="<?= $base ?>/products?cat=2"><i class="fas fa-chevron-left" aria-hidden="true"></i> پوشاک زنانه</a></li>
                    <li><a href="<?= $base ?>/products?cat=3"><i class="fas fa-chevron-left" aria-hidden="true"></i> کفش و کتونی</a></li>
                    <li><a href="<?= $base ?>/products?cat=4"><i class="fas fa-chevron-left" aria-hidden="true"></i> اکسسوری</a></li>
                    <li><a href="<?= $base ?>/products?cat=5"><i class="fas fa-chevron-left" aria-hidden="true"></i> ورزشی</a></li>
                </ul>
            </div>

            <!-- ستون تماس -->
            <div class="footer-col">
                <h3 class="footer-heading">تماس با ما</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-phone-alt" aria-hidden="true"></i> <span>۰۲۱-۱۲۳۴۵۶۷۸</span></li>
                    <li><i class="fas fa-envelope" aria-hidden="true"></i> <span>info@autumnshop.ir</span></li>
                    <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <span>تهران، خیابان ولیعصر</span></li>
                    <li><i class="fas fa-clock" aria-hidden="true"></i> <span>شنبه تا پنجشنبه ۹-۱۸</span></li>
                </ul>
            </div>
        </div>

        <!-- خط پایین -->
        <div class="footer-bottom">
            <p>© تمامی حقوق برای فروشگاه پاییزی شگفت‌انگیز محفوظ است.</p>
            <div class="footer-trust">
                <span><i class="fas fa-shield-alt" aria-hidden="true"></i> خرید امن</span>
                <span><i class="fas fa-undo" aria-hidden="true"></i> ضمانت برگشت</span>
                <span><i class="fas fa-truck" aria-hidden="true"></i> ارسال سریع</span>
            </div>
        </div>
    </div>
</footer>

<!-- اسکریپت‌ها -->
<script src="<?= $base ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= $base ?>/assets/js/slick.min.js"></script>
<script src="<?= $base ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= $base ?>/assets/js/theme-manager.js"></script>
<script src="<?= $base ?>/assets/js/main.js"></script>

</body>
</html>
