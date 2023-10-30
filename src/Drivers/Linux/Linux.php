<?php

namespace OpenSoutheners\MachineStats\Drivers\Linux;

use OpenSoutheners\MachineStats\Drivers\Driver;
use OpenSoutheners\MachineStats\OperatingSystem;

class Linux extends Driver
{
    use GeneratesCpuReports;
    use GeneratesMemoryReports;
    use GeneratesDiskReports;

    public function operatingSystem(): OperatingSystem
    {
        return OperatingSystem::Linux;
    }
}
