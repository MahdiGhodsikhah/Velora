<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>۴۰۴ – صفحه مورد نظر پیدا نشد</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/error.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
</head>
<body>

  <div class="background-particles" id="particles"></div>
  
  <div class="aurora-bg"></div>

  <div class="container">
    <div class="glitch-wrapper">
      <div class="error-code" data-text="404">404</div>
    </div>
    
    <div class="content-wrapper">
      <h1 class="message">اوه! صفحه گم شده 🔍</h1>
      <p class="subtitle">
        به نظر میاد این صفحه تو دنیای دیجیتال گم شده! <br>
        شاید آدرس اشتباه بوده یا صفحه حذف شده باشه.
      </p>

      <div class="action-buttons">
        <a href="<?php echo BASE_URL; ?>/" class="btn-home btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          صفحه اصلی
        </a>
        
        <a href="javascript:history.back()" class="btn-home btn-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          برگرد عقب
        </a>
      </div>

      <div class="suggestions">
        <p class="suggestions-title">یا میتونی اینا رو امتحان کنی:</p>
        <ul class="suggestions-list">
          <li><a href="<?php echo BASE_URL; ?>/products">محصولات</a></li>
          <li><a href="<?php echo BASE_URL; ?>/about">درباره ما</a></li>
          <li><a href="<?php echo BASE_URL; ?>/">صفحه اصلی</a></li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    // ایجاد ذرات متحرک در پس‌زمینه
    const particlesContainer = document.getElementById('particles');
    const particleCount = 25;

    for(let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      
      const size = Math.random() * 6 + 2;
      particle.style.width  = size + 'px';
      particle.style.height = size + 'px';
      
      particle.style.left   = Math.random() * 100 + 'vw';
      particle.style.top    = Math.random() * 100 + 'vh';
      
      const duration = Math.random() * 20 + 15;
      particle.style.animationDuration = duration + 's';
      particle.style.animationDelay    = Math.random() * -15 + 's';
      
      const tx = (Math.random() - 0.5) * 500;
      const ty = (Math.random() - 0.5) * 500;
      particle.style.setProperty('--tx', tx + 'px');
      particle.style.setProperty('--ty', ty + 'px');
      
      particlesContainer.appendChild(particle);
    }

    // افکت موس - فقط برای دسکتاپ
    if (window.innerWidth > 768) {
      document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        
        document.querySelector('.container').style.transform = 
          `translate(${moveX}px, ${moveY}px)`;
      });
    }
  </script>

</body>
</html>