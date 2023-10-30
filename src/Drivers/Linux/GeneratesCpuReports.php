<?php

namespace OpenSoutheners\MachineStats\Drivers\Linux;

use Exception;
use Symfony\Component\Process\Process;

trait GeneratesCpuReports
{
    public function cpuCores(): int
    {
        $process = new Process(['nproc', '--all']);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new Exception('Cannot determine CPU cores from linux system using nproc');
        }

        return (int) $process->getOutput();
    }

    // TODO: Can't get core relative usage from this /proc/stat...
    public function cpuUsage(bool $relative = false): float
    {
        $process = new Process(['head', '-n1', '/proc/stat']);

        $i = 0;
        $cpuLast = array_fill(0, 10, 0);
        $cpuLastSum = 0;

        while ($i !== 2) {
            $process->run();

            $processOutput = $process->getOutput();

            if (! $process->isSuccessful() || ! $processOutput || empty(trim($processOutput))) {
                throw new Exception('Cannot determine CPU usage from linux system using /proc/stat');
            }

            print_r($processOutput);
            $cpuNow = str_replace('cpu ', '', $processOutput);

            $cpuNow = explode(' ', $cpuNow);

            $cpuUsageSum = array_sum($cpuNow);

            $cpuDelta = $cpuUsageSum - $cpuLastSum;

            $cpuIdle = (int) $cpuNow[4] - (int) $cpuLast[4];

            $cpuLast = $cpuNow;
            $cpuLastSum = $cpuUsageSum;

            $cpuUsed = $cpuDelta - $cpuIdle;
            $cpuUsage = 100 * $cpuUsed / $cpuDelta;

            usleep(1000);
            $i++;
        }

        return round($cpuUsage, 2);
    }
}
