<?php

class AdminController {
    
    public function themeSettings(): void {
        require BASE_PATH . '/src/Views/admin/theme-settings.php';
    }
}
