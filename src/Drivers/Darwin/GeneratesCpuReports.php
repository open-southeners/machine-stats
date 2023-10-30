<?php

namespace OpenSoutheners\MachineStats\Drivers\Darwin;

use Exception;
use Symfony\Component\Process\Process;

/**
 * @mixin \OpenSoutheners\MachineStats\Drivers\Darwin\Darwin
 */
trait GeneratesCpuReports
{
    public function cpuCores(): int
    {
        $process = new Process(['sysctl', '-n', 'hw.ncpu']);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new Exception('Cannot determine CPU cores from darwin system using sysctl');
        }

        return (int) $process->getOutput();
    }

    public function cpuUsage(bool $relative = false): float
    {
        $process = new Process(['ps', '-A', '-o', '%cpu']);

        $pipeProcess = new Process(['awk', '{s+=$1} END {print s}']);

        $process->run(fn () => $pipeProcess->setInput($process->getOutput())->run());

        if (! $pipeProcess->isSuccessful()) {
            throw new Exception('Cannot determine CPU usage from darwin system using ps');
        }

        $cpuUsage = (float) $pipeProcess->getOutput();

        if ($relative) {
            $cpuUsage = $cpuUsage / $this->cpuCores();
        }

        return round($cpuUsage, 2);
    }
}
