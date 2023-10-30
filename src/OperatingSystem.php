<?php

namespace OpenSoutheners\MachineStats;

enum OperatingSystem: string
{
    case MacOS = 'darwin';

    case BSD = 'bsd';

    case Linux = 'linux';

    case Windows = 'windows';

    case Solaris = 'solaris';

    case Unknown = 'unknown';
}
