<?php

namespace OpenSoutheners\MachineStats\Tests;

use OpenSoutheners\MachineStats\Drivers\DriverInterface;
use OpenSoutheners\MachineStats\ReportResults;
use OpenSoutheners\MachineStats\StatsManager;
use PHPUnit\Framework\TestCase;

class ReportResultsTest extends TestCase
{
    public function testReportResultsSetsReadonlyPropertiesThrowsException()
    {
        $this->expectExceptionMessage('Cannot modify readonly property OpenSoutheners\MachineStats\ReportResults::$cpuCores');

        $reportResults = StatsManager::report();

        $reportResults->cpuCores = 3;
    }

    public function testReportResultsSetsReadonlyPropertiesAsArrayThrowsException()
    {
        $this->expectExceptionObject(
            new \Exception(sprintf('Class "%s" is readonly so it cannot modify its properties.', ReportResults::class))
        );

        $reportResults = StatsManager::report();

        $reportResults['cpuCores'] = 3;
    }

    public function testReportResultsUnsetsReadonlyPropertiesAsArrayThrowsException()
    {
        $this->expectExceptionObject(
            new \Exception(sprintf('Class "%s" is readonly so it cannot modify its properties.', ReportResults::class))
        );

        $reportResults = StatsManager::report();

        unset($reportResults['cpuCores']);
    }

    public function testReportResultsGetsPropertiesAsArrayItemUsingNumericKeysThrowsException()
    {
        $this->expectExceptionObject(
            new \Exception(sprintf('Property "%s" does not exists in "%s".', '0', ReportResults::class))
        );

        $reportResults = StatsManager::report();

        $reportResults[0];
    }

    public function testReportResultsHasArrayAccessProperties()
    {
        $reportResults = StatsManager::report();

        $this->assertTrue(isset($reportResults['cpuCores']));
        $this->assertIsInt($reportResults['cpuCores']);
    }

    public function testDriverGetDriverMethodReturnsCurrentDriverString()
    {
        $driver = StatsManager::newDriverInstance();

        $this->assertIsString($driver->getDriver());
    }

    public function testStatsManagerGetDriverMethodReturnsCurrentDriverObject()
    {
        $manager = new StatsManager();

        $this->assertIsObject($manager->getDriver());
        $this->assertInstanceOf(DriverInterface::class, $manager->getDriver());
    }

    public function testReportResultsGetsArrayOfCurrentStats()
    {
        $reportResults = (new StatsManager)->getReport()->toArray();

        $this->assertIsArray($reportResults);
        $this->assertNotEmpty($reportResults);

        $this->assertArrayHasKey('osType', $reportResults);
        $this->assertArrayHasKey('osTypeName', $reportResults);
        $this->assertArrayHasKey('cpuCores', $reportResults);
        $this->assertArrayHasKey('cpuUsage', $reportResults);
        $this->assertArrayHasKey('memoryUnit', $reportResults);
        $this->assertArrayHasKey('memoryUnitName', $reportResults);
        $this->assertArrayHasKey('memoryAvailable', $reportResults);
        $this->assertArrayHasKey('memoryUsage', $reportResults);
        $this->assertArrayHasKey('diskUnit', $reportResults);
        $this->assertArrayHasKey('diskUnitName', $reportResults);
        $this->assertArrayHasKey('diskAvailable', $reportResults);
        $this->assertArrayHasKey('diskUsage', $reportResults);

        $this->assertIsString($reportResults['osType']);
        $this->assertIsString($reportResults['osTypeName']);
        $this->assertIsInt($reportResults['cpuCores']);
        $this->assertGreaterThan(0, $reportResults['cpuCores']);
        $this->assertIsNumeric($reportResults['cpuUsage']);
        // TODO: This is unpredictable, could get 0 sometimes
        // $this->assertGreaterThan(0, $reportResults['cpuUsage']);
        $this->assertIsInt($reportResults['memoryUnit']);
        $this->assertIsString($reportResults['memoryUnitName']);
        $this->assertIsNumeric($reportResults['memoryAvailable']);
        $this->assertGreaterThan(0, $reportResults['memoryAvailable']);
        $this->assertIsNumeric($reportResults['memoryUsage']);
        $this->assertGreaterThan(0, $reportResults['memoryUsage']);
        $this->assertIsInt($reportResults['diskUnit']);
        $this->assertIsString($reportResults['diskUnitName']);
        $this->assertIsNumeric($reportResults['diskCapacity']);
        $this->assertGreaterThan(0, $reportResults['diskCapacity']);
        $this->assertIsNumeric($reportResults['diskAvailable']);
        $this->assertGreaterThan(0, $reportResults['diskAvailable']);
        $this->assertIsNumeric($reportResults['diskUsage']);
        $this->assertGreaterThan(0, $reportResults['diskUsage']);
    }

    public function testReportResultsAreSerialisable()
    {
        $reportResults = StatsManager::report();
        $serialisedReportResults = serialize($reportResults);

        $this->assertIsString($serialisedReportResults);
        $this->assertNotEmpty($serialisedReportResults);

        $deserialisedReportResults = unserialize($serialisedReportResults);

        $this->assertIsObject($deserialisedReportResults);
        $this->assertInstanceOf(ReportResults::class, $deserialisedReportResults);
        $this->assertNotEmpty(array_filter($deserialisedReportResults->toArray()));
    }
}
