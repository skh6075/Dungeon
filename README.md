# SmartDungeon
[Plugin] PMMP Set up monsters and Spawners!

# Developer API.

#### How use MonsterData?

* Access DungeonFactory class.
```php
DungeonFactory::getInstance();
```

* Get All MonsterDatas.
```php
/** @var MonsterData[] $datas */
$datas = DungeonFactory::getInstance()->getMonsterDatas();
```

* Get MonsterData.
```php
/** @var ?MonsterData $data */
$data = DungeonFactory::getInstance()->getMonsterData(string $name);
```

* Create MonsterData.
```php
/**
 * @param string $name
 * @param string $entityHash
 * @param float $health
 * @param float $damage
 * @param float $scale
 * @param float $speed
 * @param float $attackDistance
 * @param float $targetingDistance
 * @param string $nametagUnit
 * @param array $items
 */
DungeonFactory::getInstance()->addMonsterData($name, $entityHash, $health, $damage, $scale, $speed, $attackDistance, $targetingDistance, $nametagUnit, $items);
```

