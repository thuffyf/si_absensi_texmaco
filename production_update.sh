#!/bin/bash
# Script untuk update production setelah git pull
# Run di: /home/sitexamy/public_html/

echo "=== PRODUCTION UPDATE SCRIPT ==="
echo ""

# 1. Git Pull
echo "1. Pulling latest changes from git..."
git pull origin main
echo ""

# 2. Clear all Laravel caches
echo "2. Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ All caches cleared"
echo ""

# 3. Rebuild config cache
echo "3. Rebuilding config cache..."
php artisan config:cache
echo "✅ Config cached"
echo ""

# 4. Check latest commit
echo "4. Current commit:"
git log --oneline -1
echo ""

# 5. Verify important files
echo "5. Verifying key files modified:"
echo ""
echo "DashboardController last modified:"
ls -lh app/Http/Controllers/DashboardController.php | awk '{print $6, $7, $8, $9}'
echo ""
echo "MonitoringController last modified:"
ls -lh app/Http/Controllers/MonitoringController.php | awk '{print $6, $7, $8, $9}'
echo ""
echo "students/index.blade.php last modified:"
ls -lh resources/views/students/index.blade.php | awk '{print $6, $7, $8, $9}'
echo ""

# 6. Check if z-[100] exists in modal
echo "6. Checking if modal blur fix applied:"
if grep -q "z-\[100\]" resources/views/students/index.blade.php; then
    echo "✅ Modal z-index fix found"
else
    echo "❌ Modal z-index fix NOT found"
fi
echo ""

# 7. Check monitoring controller fix
echo "7. Checking if monitoring sync fix applied:"
if grep -q "totalAttendanceToday" app/Http/Controllers/MonitoringController.php; then
    echo "✅ Monitoring sync fix found"
else
    echo "❌ Monitoring sync fix NOT found"
fi
echo ""

echo "=== UPDATE COMPLETE ==="
echo ""
echo "Next steps:"
echo "1. Test dashboard: https://sitexa.my.id/"
echo "2. Test monitoring: https://sitexa.my.id/monitoring/nfc"
echo "3. Test add student modal: https://sitexa.my.id/students"
echo "4. Clear browser cache (Ctrl+Shift+Delete) or hard refresh (Ctrl+F5)"
echo ""
