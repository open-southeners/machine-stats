<?php

namespace OpenSoutheners\MachineStats\Drivers;

use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\MachineStats\OperatingSystem;
use OpenSoutheners\MachineStats\ReportResults;

interface DriverInterface
{
    /**
     * Generate machine report of resources usage.
     */
    public function report(
        bool $relativeCpuUsage = false,
        ?ByteUnit $memoryUnit = BinaryByteUnit::GiB,
        ?ByteUnit $diskUnit = BinaryByteUnit::GiB
    ): ReportResults;

    /**
     * Get machine's running operating system.
     */
    public function operatingSystem(): OperatingSystem;

    /**
     * Get machine's running memory available.
     */
    public function memoryAvailable(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    /**
     * Get machine's running memory usage.
     */
    public function memoryUsage(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    /**
     * Get machine's running CPU logical cores.
     */
    public function cpuCores(): int;

    /**
     * Get machine's running CPU usage (percentage).
     */
    public function cpuUsage(bool $relative = true): int|float;

    /**
     * Get machine's running total disk capacity.
     */
    public function diskCapacity(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    /**
     * Get machine's running disk capacity available.
     */
    public function diskAvailable(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    /**
     * Get machine's running disk capacity used.
     */
    public function diskUsage(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    /**
     * Get currently used driver.
     */
    public function getDriver(): string;
}
