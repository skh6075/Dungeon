<?php

namespace skh6075\dungeonplus;

use pocketmine\entity\EntityIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use skh6075\dungeonplus\entity\DungeonEntity;
use function file_get_contents;
use function is_dir;
use function mkdir;
use function array_diff;
use function scandir;
use function pathinfo;

final class DungeonPlus extends PluginBase{
    use SingletonTrait;

    /** @var DungeonData[] */
    private static array $monsters = [];

    private static array $entityNetworkIds = [];

    public function onLoad(): void{
        self::setInstance($this);

        $ref = new \ReflectionClass(EntityIds::class);
        foreach ($ref->getConstants() as $name => $value) {
            self::$entityNetworkIds[$name] = $value;
        }

        foreach (array_keys(self::$entityNetworkIds) as $name) {
            self::$entityNetworkIds[$name] = strtolower(self::$entityNetworkIds[$name]);
        }
    }

    public function onEnable(): void{
        $this->saveDefaultConfig();
        if (!is_dir($dirPath = $this->getDataFolder() . "monsters/")) {
            mkdir($dirPath);
        }

        foreach (array_diff(scandir($dirPath), ['.', '..']) as $value) {
            if (pathinfo($dirPath . $value, PATHINFO_EXTENSION) === "json") {
                self::$monsters[pathinfo($dirPath . $value, PATHINFO_FILENAME)] = json_decode(file_get_contents($dirPath . $value), true);
            }
        }
    }

    public function onDisable(): void{
        if ((bool) $this->getConfig()->getNested("disable-cleaner", true)) {
            foreach ($this->getServer()->getLevels() as $level) {
                foreach ($level->getEntities() as $entity) {
                    if ($entity instanceof DungeonEntity) {
                        $entity->flagForDespawn();
                    }
                }
            }
        }

        foreach (self::$monsters as $name => $class) {
            file_put_contents($this->getDataFolder() . "monsters/" . $name . ".json", json_encode($class->jsonSerialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public function getMonsterDataList(): array{
        return self::$monsters;
    }

    public function getMonsterData(string $name): ?DungeonData{
        return self::$monsters[$name] ?? null;
    }

    public function addMonsterData(string $name, int $entityId, float $scale, float $health, float $damage, float $attack_queue, float $attack_distance, float $targeting_distance, float $lifespan_queue): bool{
        if ($this->getMonsterData($name) instanceof DungeonData) {
            return false;
        }

        if (!isset(self::$entityNetworkIds[$entityId])) {
            return false;
        }

        self::$monsters[$name] = DungeonData::data([$name, self::$entityNetworkIds[$entityId], $scale, $health, $damage, $attack_queue, $attack_distance, $targeting_distance, $lifespan_queue, []]);
        return true;
    }

    public function deleteMonsterData(string $name): bool{
        if (!$this->getMonsterData($name) instanceof DungeonData) {
            return false;
        }

        unset(self::$monsters[$name]);
        unlink($this->getDataFolder() . "monsters/" . $name . ".json");
        return true;
    }
}