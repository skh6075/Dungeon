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
