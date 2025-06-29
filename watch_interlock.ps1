# PowerShell script to continuously watch interlock timers
# Run this script to start the watch

Write-Host "Starting Interlock Timer Watch..." -ForegroundColor Green
Write-Host "Press Ctrl+C to stop" -ForegroundColor Yellow

while ($true) {
    try {
        Set-Location "C:\laragon\www\admexport"
        php artisan interlock:check-timer
        Write-Host "Timer checked at: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Cyan
        Start-Sleep -Seconds 300  # Wait 5 minutes
    }
    catch {
        Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
        Start-Sleep -Seconds 60  # Wait 1 minute on error
    }
} 