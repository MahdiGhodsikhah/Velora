<?php
header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>۴۰۴ – صفحه مورد نظر پیدا نشد</title>
  <link rel="stylesheet" href="Error/404style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

  <div class="background-particles" id="particles"></div>

  <div class="container">
    <div class="error-code">۴۰۴</div>
    <h2 class="message">صفحه‌ای که دنبالش بودی اینجا نیست...</h2>
    <p class="subtitle">
      شاید آدرس رو اشتباه وارد کردی، یا صفحه جابه‌جا / حذف شده.
    </p>

    <a href="/" class="btn-home">
      ← بازگشت به صفحه اصلی
    </a>
  </div>

  <script>
    // ایجاد چند ذره متحرک در پس‌زمینه
    const particlesContainer = document.getElementById('particles');
    const count = 18;

    for(let i = 0; i < count; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      
      const size = Math.random() * 5 + 3;
      p.style.width  = size + 'px';
      p.style.height = size + 'px';
      
      p.style.left   = Math.random() * 100 + 'vw';
      p.style.top    = Math.random() * 100 + 'vh';
      
      const duration = Math.random() * 25 + 20;
      p.style.animationDuration = duration + 's';
      p.style.animationDelay    = Math.random() * -20 + 's';
      
      const tx = (Math.random() - 0.5) * 400;
      const ty = (Math.random() - 0.5) * 400;
      p.style.setProperty('--tx', tx + 'px');
      p.style.setProperty('--ty', ty + 'px');
      
      particlesContainer.appendChild(p);
    }
  </script>

</body>
</html>