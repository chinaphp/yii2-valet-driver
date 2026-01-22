# yii2-valet-driver

## Update Valet

```bash
composer global require laravel/valet
```

```bash
valet install
```

## Install Global Driver

```bash
cd ~/.config/valet/Drivers/
wget https://raw.githubusercontent.com/chinaphp/yii2-valet-driver/master/Yii2ValetDriver.php -O Yii2ValetDriver.php
```

## yii2-app-basic

```bash
valet link app-name
http://app-name.test
```

## yii2-app-advanced

```bash
cd backend
valet link backend-app-name
http://backend-app-name.test

cd frontend
valet link frontend-app-name
http://frontend-app-name.test
```

## Asset subdomain

```bash
cd assets
valet link assets.example
http://assets.example.test/no_image.png
```

## yii2-app-advanced-with-single-domain

```bash
valet link app-name
http://app-name.test/api/
```

## Single web directory with multiple entry points

Directory structure

```
web/
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

## Local driver inside project

Copy LocalValetDriver.php into your project root

```bash
cp LocalValetDriver.php /path/to/your-project/LocalValetDriver.php
```

Run in the project root

```bash
valet link app-name
```

Access examples

```
http://app-name.test/
http://app-name.test/api/
http://app-name.test/backend/
```
