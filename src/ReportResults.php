<?php

namespace OpenSoutheners\MachineStats;

use Exception;
use OpenSoutheners\ByteUnitConverter\ByteUnit;

class ReportResults implements \ArrayAccess
{
    public function __construct(
        public readonly OperatingSystem $os,
        public readonly int $cpuCores,
        public readonly int|float $cpuUsage,
        public readonly ByteUnit $memoryUnit,
        public readonly int|float $memoryAvailable,
        public readonly int|float $memoryUsage,
        public readonly ByteUnit $diskUnit,
        public readonly int|float $diskCapacity,
        public readonly int|float $diskAvailable,
        public readonly int|float $diskUsage
    ) {
        //
    }

    /**
     * Get array representation of this class.
     */
    public function toArray(): array
    {
        return [
            'osType' => $this->os->value,
            'osTypeName' => $this->os->name,

            'cpuCores' => $this->cpuCores,
            'cpuUsage' => $this->cpuUsage,

            'memoryUnit' => $this->memoryUnit->value,
            'memoryUnitName' => $this->memoryUnit->name,
            'memoryAvailable' => $this->memoryAvailable,
            'memoryUsage' => $this->memoryUsage,

            'diskUnit' => $this->diskUnit->value,
            'diskUnitName' => $this->diskUnit->name,
            'diskCapacity' => $this->diskCapacity,
            'diskAvailable' => $this->diskAvailable,
            'diskUsage' => $this->diskUsage,
        ];
    }

    /**
     * String representation of object.
     */
    public function __serialize(): array
    {
        $arrayProperties = $this->toArray();

        unset(
            $arrayProperties['osTypeName'],
            $arrayProperties['memoryUnitName'],
            $arrayProperties['diskUnitName']
        );

        $arrayProperties['osType'] = $this->os;
        $arrayProperties['memoryUnit'] = $this->memoryUnit;
        $arrayProperties['diskUnit'] = $this->diskUnit;

        return $arrayProperties;
    }

    /**
     * Constructs the object from serialised.
     */
    public function __unserialize(array $data): void
    {
        $this->os = $data['osType'];

        $this->cpuCores = $data['cpuCores'];
        $this->cpuUsage = $data['cpuUsage'];

        $this->memoryUnit = $data['memoryUnit'];
        $this->memoryAvailable = $data['memoryAvailable'];
        $this->memoryUsage = $data['memoryUsage'];

        $this->diskUnit = $data['diskUnit'];
        $this->diskCapacity = $data['diskCapacity'];
        $this->diskAvailable = $data['diskAvailable'];
        $this->diskUsage = $data['diskUsage'];
    }

    /**
     * Whether an offset exists.
     */
    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    /**
     * Returns the value at specified offset.
     *
     * @throws \Exception
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (! $this->offsetExists($offset)) {
            throw new Exception(
                sprintf('Property "%s" does not exists in "%s".', $offset, self::class)
            );
        }

        return $this->{$offset};
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @throws \Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception(
            sprintf('Class "%s" is readonly so it cannot modify its properties.', self::class)
        );
    }

    /**
     * Unsets an offset.
     *
     * @throws \Exception
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new Exception(
            sprintf('Class "%s" is readonly so it cannot modify its properties.', self::class)
        );
    }
}
