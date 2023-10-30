<?php

namespace OpenSoutheners\MachineStats;

use Exception;
use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\MachineStats\Drivers\Darwin\Darwin;
use OpenSoutheners\MachineStats\Drivers\DriverInterface;
use OpenSoutheners\MachineStats\Drivers\Linux\Linux;

final class StatsManager
{
    protected DriverInterface $driver;

    public function __construct(?DriverInterface $driver = null)
    {
        $this->driver = $driver ?? self::newDriverInstance();
    }

    /**
     * Get new driver instance based on current operating system.
     */
    public static function newDriverInstance(): DriverInterface
    {
        $phpOperatingSystem = strtolower(PHP_OS_FAMILY);

        return match (OperatingSystem::tryFrom($phpOperatingSystem)) {
            OperatingSystem::MacOS => new Darwin,
            OperatingSystem::Linux => new Linux,
            default => throw new Exception(sprintf('Not recognised operating system, getting "%s".', $phpOperatingSystem)),
        };
    }

    /**
     * Get machine resources report based on current operating system.
     */
    public static function report(
        bool $relativeCpuUsage = false,
        ?ByteUnit $memoryUnit = BinaryByteUnit::GiB,
        ?ByteUnit $diskUnit = BinaryByteUnit::GiB
    ): ReportResults {
        return self::newDriverInstance()->report($relativeCpuUsage, $memoryUnit, $diskUnit);
    }

    /**
     * Get machine resources report based on current operating system.
     */
    public function getReport(
        bool $relativeCpuUsage = false,
        ?ByteUnit $memoryUnit = BinaryByteUnit::GiB,
        ?ByteUnit $diskUnit = BinaryByteUnit::GiB
    ): ReportResults {
        return $this->driver->report($relativeCpuUsage, $memoryUnit, $diskUnit);
    }

    /**
     * Get currently instanced driver.
     */
    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }
}
