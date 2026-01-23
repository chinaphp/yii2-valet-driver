# Yii Valet Driver

A Laravel Valet driver for Yii Framework applications. Supports both **Yii2** and **Yii3**.

## Update Valet

```bash
composer global require laravel/valet
```

```bash
valet install
```

## Install Global Driver

Choose one of the following drivers based on your needs:

### Option 1: YiiValetDriver (Recommended)
Supports both Yii2 and Yii3 applications with automatic detection.

```bash
cd ~/.config/valet/Drivers/
wget https://raw.githubusercontent.com/chinaphp/yii2-valet-driver/master/YiiValetDriver.php -O YiiValetDriver.php
```

### Option 2: Yii2ValetDriver (Legacy)
For Yii2 applications only.

```bash
cd ~/.config/valet/Drivers/
wget https://raw.githubusercontent.com/chinaphp/yii2-valet-driver/master/Yii2ValetDriver.php -O Yii2ValetDriver.php
```

## Yii3 Application

### Standard Yii3 Structure
```
project/
├── bin/
│   └── yii           # Console bootstrap
├── config/
│   └── web.php       # Web config
├── public/
│   └── index.php     # Web entry point
├── runtime/
├── vendor/
│   └── yiisoft/yii/ # Yii3 package
└── ...
```

### Usage
```bash
cd /path/to/yii3-project
valet link app-name
```

```bash
# Access via browser
http://app-name.test
```

### Yii3 with Multiple Entry Points
Directory structure
```
public/
  index.php
  api/
    index.php
  backend/
    index.php
```

Access examples
```
http://app-name.test/
http://app-name.test/api/
http://app-name.test/backend/
```

## Yii2 Application

### yii2-app-basic
```bash
cd /path/to/yii2-basic
valet link app-name
```

```bash
# Access via browser
http://app-name.test
```

### yii2-app-advanced
```bash
cd backend
valet link backend-app-name
http://backend-app-name.test

cd frontend
valet link frontend-app-name
http://frontend-app-name.test
```

## Asset Subdomain
```bash
cd assets
valet link assets.example
http://assets.example.test/no_image.png
```

## Yii2 Advanced with Single Domain
```bash
cd /path/to/yii2-advanced
valet link app-name
```

```bash
# Access via browser
http://app-name.test/api/
```

## Single Web Directory with Multiple Entry Points

Directory structure (works for both Yii2 and Yii3)
```
web/          (Yii2) or public/ (Yii3)
  index.php
  api/
    index.php
  backend/
    index.php
  admin/
    index.php
```

Access examples
```
http://app-name.test/
http://app-name.test/api/
http://app-name.test/backend/
http://app-name.test/admin/
```

## Local Driver Inside Project

For project-specific configuration, use the local driver:

```bash
cp LocalValetDriver.php /path/to/your-project/LocalValetDriver.php
```

Run in the project root:
```bash
valet link app-name
```

Access examples
```
http://app-name.test/
http://app-name.test/api/
http://app-name.test/backend/
```

### Customizing LocalValetDriver

Edit `LocalValetDriver.php` in your project root to customize:

```php
// Change web directory priority (Yii3 first, then Yii2)
private array $webDirectories = ['public', 'web'];

// Add custom entry points
private array $entries = ['api', 'backend', 'admin', 'oauth2', 'v1', 'v2', 'custom'];
```

## Supported Directory Detection

The driver automatically detects the framework version based on:

| Indicator | Yii2 | Yii3 |
|-----------|------|------|
| Console file | `/yii` | `bin/yii` |
| Vendor package | `yiisoft/yii2` | `yiisoft/yii` |
| Web directory | `web/` | `public/` |
| Config file | - | `config/web.php` |

## Feature Comparison

| Feature | Yii2ValetDriver | YiiValetDriver | LocalValetDriver |
|---------|----------------|----------------|------------------|
| Yii2 Support | ✅ | ✅ | ✅ |
| Yii3 Support | ❌ | ✅ | ✅ |
| Auto-detection | ✅ | ✅ | ✅ |
| Asset Subdomain | ✅ | ✅ | ✅ |
| Multiple Entry Points | api, backend | api, backend, admin, oauth2, v1, v2 | Customizable |
| Static Files | ✅ | ✅ | ✅ |
| Global Installation | ✅ | ✅ | ❌ (Project-specific) |

## Migration from Yii2ValetDriver

If you're upgrading from `Yii2ValetDriver` to `YiiValetDriver`:

1. Backup your existing driver:
   ```bash
   cp ~/.config/valet/Drivers/Yii2ValetDriver.php ~/.config/valet/Drivers/Yii2ValetDriver.php.bak
   ```

2. Install the new driver:
   ```bash
   cd ~/.config/valet/Drivers/
   wget https://raw.githubusercontent.com/chinaphp/yii2-valet-driver/master/YiiValetDriver.php -O YiiValetDriver.php
   ```

3. Restart Valet:
   ```bash
   valet restart
   ```

4. Test your existing Yii2 projects - they should work without any changes!

## Troubleshooting

### Driver not detecting my project
Check if your project has one of:
- `/yii` file (Yii2) or `bin/yii` file (Yii3)
- `vendor/yiisoft/yii2` (Yii2) or `vendor/yiisoft/yii` (Yii3)
- `web/index.php` (Yii2) or `public/index.php` (Yii3)

### Static files not loading
Ensure your web directory contains the static files. The driver looks in `web/` (Yii2) or `public/` (Yii3).

### Custom entry points not working
Use `LocalValetDriver.php` and customize the `$entries` array.

## License

MIT
