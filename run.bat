@echo off
echo ========================================
echo Laravel RBAC Project Setup and Run
echo ========================================
echo.

REM Try to find PHP
set PHP_CMD=
set PHP_FOUND=0

REM First, try PHP from PATH
php --version >nul 2>&1
if not errorlevel 1 (
    set PHP_CMD=php
    set PHP_FOUND=1
    echo Using PHP from PATH
) else (
    echo PHP not found in PATH. Searching for WampServer PHP...
    
    REM Check common WampServer locations (check 8.3.6 first since user has it)
    if exist "C:\wamp64\bin\php\php8.3.6\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.3.6\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp64\bin\php\php8.3.5\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.3.5\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp64\bin\php\php8.3.4\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.3.4\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp64\bin\php\php8.3.3\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.3.3\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp64\bin\php\php8.2.0\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.2.0\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp64\bin\php\php8.1.0\php.exe" (
        set PHP_CMD=C:\wamp64\bin\php\php8.1.0\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\wamp\bin\php\php8.3.6\php.exe" (
        set PHP_CMD=C:\wamp\bin\php\php8.3.6\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    ) else if exist "C:\xampp\php\php.exe" (
        set PHP_CMD=C:\xampp\php\php.exe
        set PHP_FOUND=1
        echo Found PHP at: %PHP_CMD%
    )
    
    if %PHP_FOUND%==0 (
        echo ERROR: PHP not found!
        echo.
        echo Please install PHP first:
        echo 1. Download from: https://windows.php.net/download/
        echo 2. Extract to C:\php
        echo 3. Add C:\php to your system PATH
        echo.
        echo Or use WampServer (if installed, add PHP to PATH: C:\wamp64\bin\php\php8.x.x)
        echo Or install XAMPP from: https://www.apachefriends.org/
        pause
        exit /b 1
    )
    echo.
)

REM Check PHP version (requires 8.1+)
echo Checking PHP version...
%PHP_CMD% -r "if (version_compare(PHP_VERSION, '8.1.0', '<')) { fwrite(STDERR, 'ERROR: PHP 8.1+ required. Your version: ' . PHP_VERSION . PHP_EOL); exit(1); }" 2>nul
if errorlevel 1 (
    echo.
    echo ========================================
    echo WARNING: PHP VERSION TOO OLD!
    echo ========================================
    echo This project requires PHP 8.1 or higher.
    echo.
    echo If using WampServer:
    echo 1. Click WampServer icon -^> Tools -^> Version -^> PHP
    echo 2. Select PHP 8.1, 8.2, or 8.3
    echo 3. If not available, download from: https://windows.php.net/download/
    echo 4. Restart WampServer and try again
    echo.
    echo See QUICK_FIX.md for detailed upgrade instructions.
    echo.
    pause
    exit /b 1
)
%PHP_CMD% --version
echo PHP version OK!
echo.

REM Check for composer.phar, download if missing
if not exist composer.phar (
    echo Downloading Composer...
    powershell -Command "Invoke-WebRequest -Uri 'https://getcomposer.org/download/latest-stable/composer.phar' -OutFile 'composer.phar'"
    if errorlevel 1 (
        echo ERROR: Failed to download Composer
        pause
        exit /b 1
    )
)

echo Step 1: Installing Composer dependencies...
%PHP_CMD% composer.phar install
if errorlevel 1 (
    echo ERROR: Composer install failed.
    pause
    exit /b 1
)

echo.
echo Step 2: Generating application key...
%PHP_CMD% artisan key:generate
if errorlevel 1 (
    echo ERROR: Failed to generate key. Make sure PHP is installed and in PATH.
    pause
    exit /b 1
)

echo.
echo Step 3: Running database migrations...
%PHP_CMD% artisan migrate
if errorlevel 1 (
    echo WARNING: Migration failed. Make sure MySQL is running and database is created.
    echo Please create the database 'laravel_marga' in MySQL first.
    pause
)

echo.
echo Step 4: Seeding database...
%PHP_CMD% artisan db:seed
if errorlevel 1 (
    echo WARNING: Seeding failed.
    pause
)

echo.
echo Step 5: Creating storage link...
%PHP_CMD% artisan storage:link
if errorlevel 1 (
    echo WARNING: Storage link creation failed.
    pause
)

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Starting development server...
echo The application will be available at http://localhost:8000
echo.
echo Default login credentials:
echo Email: admin@example.com
echo Password: password
echo.
echo Press Ctrl+C to stop the server
echo.

%PHP_CMD% artisan serve

