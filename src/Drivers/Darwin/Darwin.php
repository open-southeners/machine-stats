<?php

namespace OpenSoutheners\MachineStats\Drivers\Darwin;

use OpenSoutheners\MachineStats\Drivers\Driver;
use OpenSoutheners\MachineStats\OperatingSystem;

class Darwin extends Driver
{
    use GeneratesCpuReports;
    use GeneratesMemoryReports;
    use GeneratesDiskReports;

    public function operatingSystem(): OperatingSystem
    {
        return OperatingSystem::MacOS;
    }
}
