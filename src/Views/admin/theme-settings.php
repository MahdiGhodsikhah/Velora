<?php
$configPath = BASE_PATH . '/config/theme.php';
$config = require $configPath;
?>

<?php if (isset($_SESSION['admin_success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['admin_success'] ?>
        <?php unset($_SESSION['admin_success']); ?>
    </div>
<?php endif; ?>

<div class="admin-table">
    <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1e293b;">تنظیمات تم سایت</h3>
        <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #64748b;">
            می‌توانید تم سایت را به صورت خودکار بر اساس فصل جاری یا به صورت دستی انتخاب کنید.
        </p>
    </div>
    
    <div style="padding: 2rem;">
        <form method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold" style="color: #1e293b; margin-bottom: 1rem;">حالت تم</label>
                
                <div class="form-check mb-3" style="padding: 1rem; background: #f8fafc; border-radius: 8px; border: 2px solid <?= $config['mode'] === 'automatic' ? '#4f46e5' : '#e2e8f0' ?>;">
                    <input class="form-check-input" type="radio" name="mode" id="mode_auto" value="automatic" 
                           <?= $config['mode'] === 'automatic' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="mode_auto" style="cursor: pointer;">
                        <div style="font-weight: 600; color: #1e293b;">خودکار (توصیه می‌شود)</div>
                        <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">
                            تم به صورت خودکار بر اساس فصل جاری تغییر می‌کند
                        </div>
                    </label>
                </div>
                
                <div class="form-check" style="padding: 1rem; background: #f8fafc; border-radius: 8px; border: 2px solid <?= $config['mode'] === 'manual' ? '#4f46e5' : '#e2e8f0' ?>;">
                    <input class="form-check-input" type="radio" name="mode" id="mode_manual" value="manual"
                           <?= $config['mode'] === 'manual' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="mode_manual" style="cursor: pointer;">
                        <div style="font-weight: 600; color: #1e293b;">دستی</div>
                        <div style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">
                            شما به صورت دستی تم مورد نظر خود را انتخاب می‌کنید
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="mb-4" id="theme_selector" style="<?= $config['mode'] === 'manual' ? '' : 'display:none;' ?>">
                <label for="admin_theme" class="form-label fw-bold" style="color: #1e293b;">انتخاب تم</label>
                <select class="form-select" id="admin_theme" name="admin_theme" style="padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <option value="spring" <?= ($config['admin_selected_theme'] ?? '') === 'spring' ? 'selected' : '' ?>>🌸 بهار (Spring)</option>
                    <option value="summer" <?= ($config['admin_selected_theme'] ?? '') === 'summer' ? 'selected' : '' ?>>☀️ تابستان (Summer)</option>
                    <option value="autumn" <?= ($config['admin_selected_theme'] ?? '') === 'autumn' ? 'selected' : '' ?>>🍂 پاییز (Autumn)</option>
                    <option value="winter" <?= ($config['admin_selected_theme'] ?? '') === 'winter' ? 'selected' : '' ?>>❄️ زمستان (Winter)</option>
                </select>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600;">
                    💾 ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('mode_auto').addEventListener('change', function() {
        document.getElementById('theme_selector').style.display = 'none';
        // Update border colors
        this.closest('.form-check').style.borderColor = '#4f46e5';
        document.getElementById('mode_manual').closest('.form-check').style.borderColor = '#e2e8f0';
    });
    document.getElementById('mode_manual').addEventListener('change', function() {
        document.getElementById('theme_selector').style.display = 'block';
        // Update border colors
        this.closest('.form-check').style.borderColor = '#4f46e5';
        document.getElementById('mode_auto').closest('.form-check').style.borderColor = '#e2e8f0';
    });
</script>
