/**
 * اسکریپت Debug پیشرفته برای بررسی استایل‌های کارت محصول
 */

document.addEventListener('DOMContentLoaded', function() {
    const grid = document.querySelector('.products-grid');
    const cards = document.querySelectorAll('.products-grid > div');
    
    if (!grid || cards.length === 0) {
        console.log('Grid یا کارت‌ها پیدا نشد');
        return;
    }
    
    function debugGrid() {
        console.log('%c=== Debug اطلاعات Grid محصولات ===', 'background: #d97706; color: white; padding: 5px; font-weight: bold;');
        console.log('تعداد کارت‌ها:', cards.length);
        
        const gridStyles = window.getComputedStyle(grid);
        console.log('عرض Grid:', gridStyles.width);
        console.log('Gap Grid:', gridStyles.gap);
        console.log('Display Grid:', gridStyles.display);
        console.log('Flex-wrap Grid:', gridStyles.flexWrap);
        
        cards.forEach((card, index) => {
            if (index < 3) {
                const styles = window.getComputedStyle(card);
                const rect = card.getBoundingClientRect();
                
                console.log(`%c\nکارت ${index + 1}:`, 'color: #10b981; font-weight: bold;');
                console.log('  Width (computed):', styles.width);
                console.log('  Width (actual):', rect.width + 'px');
                console.log('  Max-width:', styles.maxWidth);
                console.log('  Min-width:', styles.minWidth);
                console.log('  Flex:', styles.flex);
                console.log('  Flex-grow:', styles.flexGrow);
                console.log('  Flex-shrink:', styles.flexShrink);
                console.log('  Flex-basis:', styles.flexBasis);
                console.log('  Box-sizing:', styles.boxSizing);
                
                // بررسی همه CSS rules که روی این element تاثیر می‌گذارند
                const allRules = [];
                for (let sheet of document.styleSheets) {
                    try {
                        for (let rule of sheet.cssRules) {
                            if (rule.selectorText && card.matches(rule.selectorText)) {
                                if (rule.style.width || rule.style.maxWidth || rule.style.flex) {
                                    allRules.push({
                                        selector: rule.selectorText,
                                        width: rule.style.width,
                                        maxWidth: rule.style.maxWidth,
                                        flex: rule.style.flex
                                    });
                                }
                            }
                        }
                    } catch (e) {}
                }
                
                if (allRules.length > 0) {
                    console.log('  CSS Rules:', allRules);
                }
            }
        });
    }
    
    debugGrid();
    
    // بررسی عرض واقعی صفحه
    console.log('%c\n=== اطلاعات صفحه ===', 'background: #3b82f6; color: white; padding: 5px; font-weight: bold;');
    console.log('عرض پنجره:', window.innerWidth + 'px');
    console.log('عرض صفحه:', document.documentElement.clientWidth + 'px');
    console.log('عرض Grid Container:', grid.offsetWidth + 'px');
    console.log('عرض Parent از Grid:', grid.parentElement.offsetWidth + 'px');
    
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
        
        if (!isCorrect) {
            console.log('%c⚠️ مشکل: تعداد ستون‌ها اشتباه است!', 'background: #dc2626; color: white; padding: 5px; font-weight: bold;');
            
            // محاسبه اینکه چرا width بزرگ شده
            const firstCard = cards[0];
            const styles = window.getComputedStyle(firstCard);
            const widthValue = parseFloat(styles.width);
            const maxWidthValue = parseFloat(styles.maxWidth);
            
            console.log('تحلیل Width:');
            console.log('  Width محاسبه شده:', widthValue);
            console.log('  Max-width محاسبه شده:', maxWidthValue);
            console.log('  Width باید باشد:', grid.offsetWidth / expectedColumns - 20);
            
            if (widthValue > maxWidthValue * 2) {
                console.log('%c🔴 مشکل: Width بیش از حد بزرگ است! احتمالاً calc() درست کار نمی‌کند', 'background: #dc2626; color: white; padding: 5px;');
            }
        }
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
            
            let expectedColumns = 0;
            if (window.innerWidth >= 1024) {
                expectedColumns = 3;
            } else if (window.innerWidth >= 600) {
                expectedColumns = 2;
            } else {
                expectedColumns = 1;
            }
            
            const isCorrect = cardsInRow === Math.min(cards.length, expectedColumns);
            const statusIcon = isCorrect ? '✅' : '❌';
            
            console.log('\n%c=== بعد از Resize (' + window.innerWidth + 'px) ===', 'background: #f59e0b; color: white; padding: 5px;');
            console.log('Width کارت اول:', styles.width);
            console.log('Max-width کارت اول:', styles.maxWidth);
            console.log(statusIcon + ' تعداد کارت در ردیف:', cardsInRow, '(انتظار: ' + expectedColumns + ')');
            
            if (!isCorrect) {
                const widthValue = parseFloat(styles.width);
                const grid = document.querySelector('.products-grid');
                const expectedWidth = grid.offsetWidth / expectedColumns - 20;
                console.log('⚠️ Width باید باشد حدود:', expectedWidth.toFixed(2) + 'px');
                console.log('⚠️ ولی هست:', widthValue.toFixed(2) + 'px');
            }
        }
    }, 500);
});
