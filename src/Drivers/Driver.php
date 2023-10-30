<?php

namespace OpenSoutheners\MachineStats\Drivers;

use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\MachineStats\OperatingSystem;
use OpenSoutheners\MachineStats\ReportResults;

abstract class Driver implements DriverInterface
{
    final public function report(
        bool $relativeCpuUsage = false,
        ?ByteUnit $memoryUnit = BinaryByteUnit::GiB,
        ?ByteUnit $diskUnit = BinaryByteUnit::GiB
    ): ReportResults {
        return new ReportResults(
            os: $this->operatingSystem(),
            memoryUnit: $memoryUnit,
            memoryAvailable: $this->memoryAvailable($memoryUnit),
            memoryUsage: $this->memoryUsage($memoryUnit),
            cpuCores: $this->cpuCores(),
            cpuUsage: $this->cpuUsage($relativeCpuUsage),
            diskUnit: $diskUnit,
            diskCapacity: $this->diskCapacity($diskUnit),
            diskAvailable: $this->diskAvailable($diskUnit),
            diskUsage: $this->diskUsage($diskUnit),
        );
    }

    public function operatingSystem(): OperatingSystem
    {
        return OperatingSystem::Unknown;
    }

    abstract public function memoryAvailable(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    abstract public function memoryUsage(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    abstract public function cpuCores(): int;

    abstract public function cpuUsage(bool $relative = true): int|float;

    abstract public function diskCapacity(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    abstract public function diskAvailable(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    abstract public function diskUsage(ByteUnit $byteUnit = BinaryByteUnit::GiB): int|float;

    public function getDriver(): string
    {
        return self::class;
    }
}
