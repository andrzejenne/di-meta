<?php


namespace BigBIT\DIMeta;


/**
 * Class ClassMetaData
 * @package BigBIT\DIMeta
 */
class ClassMetaData implements \JsonSerializable, \Iterator, \ArrayAccess
{
    /** @var ArgMetaData[] */
    private $items = [];

    /** @var int */
    private $position = 0;

    /**
     * @param ArgMetaData $item
     */
    public function add(ArgMetaData $item): void
    {
        $this->items[] = $item;
    }

    public function jsonSerialize(): array
    {
        $serialized = [];
        foreach ($this->items as $item) {
            $serialized[] = $item->__serialize();
        }

        return $serialized;        
    }

    /**
     * @return array<array>
     */
    public function __serialize(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @param array<array> $data
     */
    public function __unserialize(array $data): void
    {
        foreach ($data as $serializedItem) {
            $this->items[] = new ArgMetaData($serializedItem[0], $serializedItem[1], $serializedItem[2], $serializedItem[3]);
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current() 
    {
        return $this->items[$this->position];
    }

    public function key() 
    {
        return $this->position;
    }

    public function next() 
    {
        ++$this->position;
    }

    public function valid() 
    {
        return isset($this->items[$this->position]);
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }
    
    public function offsetGet($offset): ArgMetaData
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof ArgMetaData) {
            throw new \Exception("Invalid type in value");
        }

        $this->items[$offset] = $value;
    }
    
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}
