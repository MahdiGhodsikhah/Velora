/**
 * اسکریپت Debug برای بررسی استایل‌های کارت محصول
 */

document.addEventListener('DOMContentLoaded', function() {
    const grid = document.querySelector('.products-grid');
    const cards = document.querySelectorAll('.products-grid > div');
    
    if (!grid || cards.length === 0) {
        console.log('Grid یا کارت‌ها پیدا نشد');
        return;
    }
    
    console.log('%c=== Debug اطلاعات Grid محصولات ===', 'background: #d97706; color: white; padding: 5px; font-weight: bold;');
    console.log('تعداد کارت‌ها:', cards.length);
    console.log('عرض Grid:', window.getComputedStyle(grid).width);
    console.log('Gap Grid:', window.getComputedStyle(grid).gap);
    console.log('Display Grid:', window.getComputedStyle(grid).display);
    
    cards.forEach((card, index) => {
        if (index < 3) {
            const styles = window.getComputedStyle(card);
            console.log(`%c\nکارت ${index + 1}:`, 'color: #10b981; font-weight: bold;');
            console.log('  Width:', styles.width);
            console.log('  Max-width:', styles.maxWidth);
            console.log('  Flex:', styles.flex);
            console.log('  Flex-basis:', styles.flexBasis);
            console.log('  Box-sizing:', styles.boxSizing);
        }
    });
    
    // بررسی عرض واقعی صفحه
    console.log('%c\n=== اطلاعات صفحه ===', 'background: #3b82f6; color: white; padding: 5px; font-weight: bold;');
    console.log('عرض پنجره:', window.innerWidth + 'px');
    console.log('عرض صفحه:', document.documentElement.clientWidth + 'px');
    
    // تشخیص breakpoint
    let breakpoint = '';
    let expectedColumns = 0;
    if (window.innerWidth >= 1024) {
        breakpoint = '🖥️ Desktop (≥1024px)';
        expectedColumns = 3;
    } else if (window.innerWidth >= 600) {
        breakpoint = '📱 Tablet (600-1023px)';
        expectedColumns = 2;
    } else {
        breakpoint = '📱 Mobile (<600px)';
        expectedColumns = 1;
    }
    console.log('%c' + breakpoint + ' - باید ' + expectedColumns + ' ستون باشد', 'background: #dc2626; color: white; padding: 5px; font-weight: bold;');
    
    // محاسبه تعداد کارت در هر ردیف
    const cardsInRow = calculateCardsPerRow(cards);
    const actualCards = Math.min(cards.length, expectedColumns);
    const isCorrect = cardsInRow === actualCards;
    const emoji = isCorrect ? '✅' : '❌';
    const color = isCorrect ? '#10b981' : '#dc2626';
    
    if (cards.length < expectedColumns) {
        console.log(`%c✅ تعداد واقعی کارت در ردیف اول: ${cardsInRow} (تعداد کل محصولات: ${cards.length})`, `background: #10b981; color: white; padding: 5px; font-weight: bold;`);
        console.log('%cℹ️ کمتر از حد انتظار محصول وجود دارد، این طبیعی است.', 'color: #3b82f6; font-weight: bold;');
    } else {
        console.log(`%c${emoji} تعداد واقعی کارت در ردیف اول: ${cardsInRow} (انتظار: ${expectedColumns})`, `background: ${color}; color: white; padding: 5px; font-weight: bold;`);
    }
});

function calculateCardsPerRow(cards) {
    if (cards.length < 2) return 1;
    
    const firstCardTop = cards[0].getBoundingClientRect().top;
    let count = 1;
    
    for (let i = 1; i < cards.length; i++) {
        const cardTop = cards[i].getBoundingClientRect().top;
        if (Math.abs(cardTop - firstCardTop) < 5) {
            count++;
        } else {
            break;
        }
    }
    
    return count;
}

// بررسی تغییرات در resize
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
        const cards = document.querySelectorAll('.products-grid > div');
        if (cards.length > 0) {
            const firstCard = cards[0];
            const styles = window.getComputedStyle(firstCard);
            const cardsInRow = calculateCardsPerRow(cards);
            
            console.log('\n%c=== بعد از Resize (' + window.innerWidth + 'px) ===', 'background: #f59e0b; color: white; padding: 5px;');
            console.log('Width کارت اول:', styles.width);
            console.log('Max-width کارت اول:', styles.maxWidth);
            console.log('✅ تعداد کارت در ردیف:', cardsInRow);
        }
    }, 500);
});
