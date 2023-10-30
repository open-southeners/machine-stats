Machine Stats [![required php version](https://img.shields.io/packagist/php-v/open-southeners/machine-stats)](https://www.php.net/supported-versions.php) [![codecov](https://codecov.io/gh/open-southeners/machine-stats/branch/main/graph/badge.svg?token=DGZOCVFLWR)](https://codecov.io/gh/open-southeners/machine-stats) [![Edit on VSCode online](https://img.shields.io/badge/vscode-edit%20online-blue?logo=visualstudiocode)](https://vscode.dev/github/open-southeners/machine-stats)
===

A platform agnostic machine stats generator (PHP 8.1+). Usage and totals for CPU, disk, memory...

## Getting started

```bash
composer require open-southeners/machine-stats
```

### Usage

```php
use OpenSoutheners\MachineStats\StatsManager;

$report = StatsManager::report();

$report->os->value; // 'linux'
$report->cpuCores; // 8
$report->cpuUsage; // 17.12
$report->memoryUnit->name; // 'GB'
$report->memoryAvailable; // 11.89
$report->memoryUsed; // 4.11
$report->diskUnit->name; // 'GB'
$report->diskCapacity; // 200
$report->diskAvailable; // 189
$report->diskUsed; // 11
```

#### Using Laravel

If you are using Laravel you can simply inject 2 instances into [Laravel's container](https://laravel.com/docs/10.x/container). Add the following on your `AppServiceProvider.php` file at the `register` method:

```php
use OpenSoutheners\MachineStats\StatsManager;
use OpenSoutheners\MachineStats\Drivers\DriverInterface;

$this->app->bind(StatsManager::class, fn () => new StatsManager);
$this->app->bind(DriverInterface::class, fn (Application $app) => $app->make(StatsManager::class)->getDriver());
```

**Note: StatsManager is only a wrapper to the Driver, it mimics the Manager code design pattern that Laravel uses in many places like DB, Filesystem, etc. In our case we needed to separate between operating systems (as drivers).**

## Partners

[![skore logo](https://github.com/open-southeners/partners/raw/main/logos/skore_logo.png)](https://getskore.com)

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
