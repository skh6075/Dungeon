# SmartDungeon
[Plugin] PMMP Set up monsters and Spawners!

# Support Developer API.

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

* Delete MonsterData.
```php
/**
 * @param string $name
 */
DungeonFactory::getInstance()->deleteMonsterData($name);

* Manage MonsterData.
```php
/** @var MonsterData $data */
$data->getName();                           //get monster name
$data->getEntityHash();                     //get monster entityHash 'ex) minecraft:zombie'
$data->getHealth();                         //get monster health
$data->getDamage();                         //get monster attack damage
$data->getScale();                          //get monster scale
$data->getSpeed();                          //get monster movementSpeed
$data->getAttackDistance();                 //get monster attack distance
$data->getTargetingDistance();              //get monster targeting distance
$data->getNametagUnit();                    //get monster nametag unit
$data->getItems();                          //get monster drop items

$data->setEntityHash(string $hash);         //set monster entityHash
$data->setHealth(float $amount);            //set monster health
$data->setDamage(float $amount);            //set monster attack damage
$data->setScale(float $amount);             //set monster scale
$data->setSpeed(float $amount);             //set monster movementSpeed
$data->setAttackDistance(float $amount);    //set monster attack distance
$data->setTargetingDistance(float $amount); //set monster targeting distance
$data->addItem(Item $item, float $percent); //add monster drop reward item
$data->deleteItem(int $index);              //delete monster drop reward item
```

* Get All SpawnerDatas.
```php
/** @var SpawnerData[] $datas */
$datas = DungeonFactory::getInstance()->getSpawnerDatas();
```

* Get SpawnerData.
```php
/** @var SpawnerData $data */
$data = DungeonFactory::getInstance()->getSpawnerData(string $hash);
```

* Create SpawnerData.
```php
/**
 * @param string $posHash
 * @param string $entityName
 * @param float $playerCheckDistance
 * @param float $spawnRadius
 * @param int $spawnTick
 * @description spawntick 1 -> 1sec
 */
DungeonFactory::getInstance()->addSpawnerData($posHash, $entityName, $playerCheckDistance, $spawnRadius, $spawnTick);
```

* Manage SpawnerData.
```php
/** @var SpawnerData $data */
$data->getPosHash();                                //get Spawner PosHash
$data->getEntityName();                             //get Spawner EntityName
$data->getPlayerCheckDistance();                    //get Spawner PlayerCheckDistance
$data->getSpawnRadius();                            //get Spawner SpawnRadius
$data->getSpawnTick();                              //get Spawner SpawnTick

$data->setEntityName(string $name);                 //set Spawner EntityName
$data->setPlayerCheckDistance(float $distance);     //set Spawner PlayerCheckDistance
$data->getSpawnRadius(float $distance);             //set Spawner SpawnRadius
$data->setSpawnTick(int $tick);                     //set Spawner SpawnTick
```

# Support Entity AI.

 [O] Support Entity Auto Walking.
 [O] Support Entity Auto Swimming.
 [O] Support Entity Auto Jumping.
 [X] Support Entity Auto Flying.
 [O] Support Entity Target Entity Following.
