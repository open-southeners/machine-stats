<?php

namespace OpenSoutheners\MachineStats\Drivers\Darwin;

use Exception;
use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use Symfony\Component\Process\Process;

trait GeneratesMemoryReports
{
    /**
     * Get used memory from darwin based machines.
     *
     * @throws \Exception
     */
    public function memoryUsage(ByteUnit $unit = BinaryByteUnit::GiB): float|int
    {
        $vmStatProcess = new Process(['/usr/bin/vm_stat']);
        $sysCtlProcess = new Process(['/usr/sbin/sysctl', '-n', 'vm.page_pageable_internal_count']);

        $vmStatProcess->run();
        $sysCtlProcess->run();

        if (! $vmStatProcess->isSuccessful() || ! $sysCtlProcess->isSuccessful()) {
            throw new Exception('Cannot determine memory from darwin system using vm_stat and sysctl');
        }

        $vmStatOutput = $vmStatProcess->getOutput();
        $sysCtlOutput = $sysCtlProcess->getOutput();
        $matches = [];

        // Looking at "Pages purgeable:" to do the math with "page_pageable_internal_count"
        // https://apple.stackexchange.com/a/313930
        preg_match_all('/(Pages occupied by compressor|Pages wired down|Pages purgeable):\s+(\d+)./m', $vmStatOutput, $matches, PREG_SET_ORDER, 0);

        $totalMatched = 0;

        foreach ($matches as $match) {
            $totalMatched += $match[1] === 'Pages purgeable' ? (int) $sysCtlOutput - (int) end($match) : (int) end($match);
        }

        return round(ByteUnitConverter::conversion($totalMatched * 4096, BinaryByteUnit::B, $unit), 2);
    }

    /**
     * Get total memory from darwin based machines.
     *
     * @throws \Exception
     */
    public function memoryAvailable(ByteUnit $unit = BinaryByteUnit::GiB): float|int
    {
        $process = new Process(['/usr/sbin/sysctl', '-n', 'hw.memsize']);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new Exception('Cannot determine memory from darwin system using sysctl');
        }

        return ByteUnitConverter::conversion((int) $process->getOutput(), BinaryByteUnit::B, $unit);
    }
}
