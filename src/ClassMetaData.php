<?php


namespace BigBIT\DIMeta;


/**
 * Class ClassMetaData
 * @package BigBIT\DIMeta
 */
class ClassMetaData
{
    /** @var ArgMetaData[] */
    private $items = [];

    /**
     * @param ArgMetaData $item
     */
    public function add(ArgMetaData $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return array<array>
     */
    public function __serialize(): array
    {
        $serialized = [];
        foreach ($this->items as $item) {
            $serialized[] = $item->__serialize();
        }

        return $serialized;
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

}
