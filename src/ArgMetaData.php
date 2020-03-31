<?php


namespace BigBIT\DIMeta;

/**
 * Class ClassMetaData
 * @package BigBIT\DIMeta
 */
class ArgMetaData
{
    /**
     * @param \ReflectionParameter $reflectionParameter
     * @return static
     */
    public static function createFromReflection(\ReflectionParameter $reflectionParameter): ArgMetaData {
        $type = $reflectionParameter->getType();

        $argName = $reflectionParameter->getName();
        if ($type instanceof \ReflectionType) {
            $isBuiltin = $type->isBuiltin();
            $allowsNull = $type->allowsNull();
        } else {
            $isBuiltin = true;
            $allowsNull = true;
        }

        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();
        } else {
            $typeName = null;
        }

        return new static($argName, $isBuiltin, $typeName, $allowsNull);
    }

    /** @var string */
    public $name;

    /** @var string|null */
    public $type;

    /** @var bool */
    public $isBuiltin;

    /** @var bool */
    public $allowsNull;

    /**
     * DIMetaItem constructor.
     * @param string $name
     * @param bool $isBuiltin
     * @param string|null $type
     * @param bool $allowsNull
     */
    public final function __construct(string $name, bool $isBuiltin, ?string $type, bool $allowsNull)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isBuiltin = $isBuiltin;
        $this->allowsNull = $allowsNull;
    }

    /**
     * @return array<int, bool|string|null>
     */
    public function __serialize(): array
    {
        return [
            $this->name, $this->type, $this->isBuiltin, $this->allowsNull
        ];
    }

    /**
     * @param array<array> $data
     */
    public function __unserialize(array $data): void
    {
        list($this->name, $this->type, $this->isBuiltin, $this->allowsNull) = $data;
    }
}
