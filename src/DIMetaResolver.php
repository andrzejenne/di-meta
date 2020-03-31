<?php
namespace BigBIT\DIMeta;


use Psr\SimpleCache\CacheInterface;

/**
 * Class DIMetaResolver
 * @package BigBIT\DIMeta
 */
class DIMetaResolver
{
    /**
     * @param CacheInterface $cache
     * @return static
     */
    public static function create(CacheInterface $cache): DIMetaResolver
    {
        return new static($cache);
    }

    /**
     * @param string $id
     * @return ClassMetaData
     * @throws \ReflectionException
     */
    private static function createClassMeta(string $id): ClassMetaData
    {
        $reflection = new \ReflectionClass($id);

        $constructor = $reflection->getConstructor();

        $metaData = new ClassMetaData();

        if (!$constructor) {
            return $metaData;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $metaData->add(
                ArgMetaData::createFromReflection($parameter)
            );
        }

        return $metaData;
    }

    /**
     * @var CacheInterface
     */
    private $cache;

    public final function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $id
     * @return ClassMetaData
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getClassMeta(string $id): ClassMetaData
    {
        if (!$this->cache->has($id)) {
            return $this->resolve($id);
        }

        return $this->cache->get($id);
    }

    /**
     * @param string $id
     * @return ClassMetaData
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    private function resolve(string $id): ClassMetaData
    {
        $metaData = static::createClassMeta($id);

        $this->cache->set($id,$metaData);

        return $metaData;
    }
}
