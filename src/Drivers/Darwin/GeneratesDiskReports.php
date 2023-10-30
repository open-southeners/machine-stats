<?php

namespace OpenSoutheners\MachineStats\Drivers\Darwin;

use Exception;
use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use Symfony\Component\Process\Process;

trait GeneratesDiskReports
{
    public function diskCapacity(ByteUnit $unit = BinaryByteUnit::GiB): int|float
    {
        return ByteUnitConverter::conversion(
            $this->diskAvailable(BinaryByteUnit::B) + $this->diskUsage(BinaryByteUnit::B),
            BinaryByteUnit::B,
            $unit
        );
    }

    public function diskAvailable(ByteUnit $unit = BinaryByteUnit::GiB): int|float
    {
        $process = new Process(['df']);
        $pipeProcess = new Process(['awk', '/\/dev\/disk*/ && ! /\/private\/var\/vm/ {byte=$2} END {print byte}']);

        $process->run(fn () => $pipeProcess->setInput($process->getOutput())->run());

        $processResult = $pipeProcess->getOutput();

        if (! $pipeProcess->isSuccessful() || ! is_numeric($processResult)) {
            throw new Exception('Cannot determine memory from darwin system using df');
        }

        $diskUsageBlocks = (int) $processResult;

        $diskUsageBytes = $diskUsageBlocks * 512;

        if ($unit->value === 0) {
            return $diskUsageBytes;
        }

        return round(
            ByteUnitConverter::conversion($diskUsageBytes, BinaryByteUnit::B, $unit),
            2
        );
    }

    public function diskUsage(ByteUnit $unit = BinaryByteUnit::GiB): int|float
    {
        $process = new Process(['df']);
        $pipeProcess = new Process(['awk', '/\/dev\/disk*/ && ! /\/private\/var\/vm/ {byte=$4} END {print byte}']);

        $process->run(fn () => $pipeProcess->setInput($process->getOutput())->run());

        $processResult = $pipeProcess->getOutput();

        if (! $pipeProcess->isSuccessful() || ! is_numeric($processResult)) {
            throw new Exception('Cannot determine memory from darwin system using df');
        }

        $diskUsageBlocks = (int) $processResult;

        $diskUsageBytes = $diskUsageBlocks * 512;

        if ($unit->value === 0) {
            return $diskUsageBytes;
        }

        return round(
            ByteUnitConverter::conversion($diskUsageBytes, BinaryByteUnit::B, $unit),
            2
        );
    }
}
