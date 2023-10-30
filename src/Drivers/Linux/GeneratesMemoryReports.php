<?php

namespace OpenSoutheners\MachineStats\Drivers\Linux;

use Exception;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use OpenSoutheners\ByteUnitConverter\DecimalByteUnit;
use Symfony\Component\Process\Process;

trait GeneratesMemoryReports
{
    public function memoryAvailable(ByteUnit $unit = DecimalByteUnit::GB, bool $string = false): int|float
    {
        $memoryStats = $this->getMemoryStats();

        return ByteUnitConverter::conversion($memoryStats['MemFree'], DecimalByteUnit::KB, $unit);
    }

    public function memoryUsage(ByteUnit $unit = DecimalByteUnit::GB, bool $string = false): int|float
    {
        $memoryStats = $this->getMemoryStats();

        $memoryUsage = $memoryStats['MemTotal'] - $memoryStats['MemFree'];

        return ByteUnitConverter::conversion($memoryUsage, DecimalByteUnit::KB, $unit);
    }

    /**
     * Get memory stats from "/proc/meminfo" file parsed as a key-value array.
     *
     * @return array<string, int>
     */
    protected function getMemoryStats(): array
    {
        $process = new Process(['head', '-n3', '/proc/meminfo']);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new Exception('Cannot determine memory from linux system using /proc/meminfo');
        }

        $output = $process->getOutput();

        $outputRows = explode("\n", $output);
        $memoryStats = [];

        foreach ($outputRows as $row) {
            if (! str_contains($row, ':')) {
                continue;
            }

            [$statKey, $statValue] = explode(':', $row);

            if (! $statValue) {
                continue;
            }

            [$statValue, $statUnit] = explode(' ', trim($statValue));

            $memoryStats[$statKey] = (int) $statValue;
        }

        return $memoryStats;
    }
}
