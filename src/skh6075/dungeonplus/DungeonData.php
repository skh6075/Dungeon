<?php

namespace skh6075\dungeonplus;

use pocketmine\item\Item;
use function array_values;
use function mt_rand;
use function count;

final class DungeonData implements \JsonSerializable{

    private string $name;

    private string $entityType;

    private float $scale;

    private float $health;

    private float $damage;

    private float $attack_queue;

    private float $attack_distance;

    private float $targeting_distance;

    private float $lifespan_queue;

    private array $items = [];

    public function __construct(string $name, string $entityType, float $scale, float $health, float $damage, float $attack_queue, float $attack_distance, float $targeting_distance, float $lifespan_queue, array $items) {
        $this->name = $name;
        $this->entityType = $entityType;
        $this->scale = $scale;
        $this->health = $health;
        $this->damage = $damage;
        $this->attack_queue = $attack_queue;
        $this->attack_distance = $attack_distance;
        $this->targeting_distance = $targeting_distance;
        $this->lifespan_queue = $lifespan_queue;
        $this->items = $items;
    }

    public static function data(array $data): self{
        return new DungeonData(...$data);
    }

    public function jsonSerialize(): array{
        return [
            $this->name,
            $this->entityType,
            $this->scale,
            $this->health,
            $this->damage,
            $this->attack_queue,
            $this->attack_distance,
            $this->targeting_distance,
            $this->lifespan_queue,
            $this->items
        ];
    }

    public function getName(): string{
        return $this->name;
    }

    public function getEntityType(): string{
        return $this->entityType;
    }

    public function getScale(): float{
        return $this->scale;
    }

    public function setScale(float $scale): void{
        $this->scale = $scale;
    }

    public function getHealth(): float{
        return $this->health;
    }

    public function setHealth(float $health): void{
        $this->health = $health;
    }

    public function getDamage(): float{
        return $this->damage;
    }

    public function setDamage(float $damage): void{
        $this->damage = $damage;
    }

    public function getAttackQueue(): float{
        return $this->attack_queue;
    }

    public function setAttackQueue(float $queue): void{
        $this->attack_queue = $queue;
    }

    public function getAttackDistance(): float{
        return $this->attack_distance;
    }

    public function setAttackDistance(float $distance): void{
        $this->attack_distance = $distance;
    }

    public function getTargetingDistance(): float{
        return $this->targeting_distance;
    }

    public function setTargetingDistance(float $distance): void{
        $this->targeting_distance = $distance;
    }

    public function getLifeSpanQueue(): float{
        return $this->lifespan_queue;
    }

    public function setLifeSpanQueue(float $queue): void{
        $this->lifespan_queue = $queue;
    }

    public function addRewardItem(Item $item, float $percent): void{
        if ($percent > 100.0)
            $percent = 100.0;

        $this->items[] = [$item, $percent];
    }

    public function deleteRewardItem(int $key): void{
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
            $this->items = array_values($this->items);
        }
    }

    public function getRandomRewardItem(): ?Item{
        $res = [];
        foreach ($this->items as $item) {
            if (mt_rand(1, 100) <= $item[1])
                $res[] = Item::jsonDeserialize($item[0]);
        }

        return count($res) > 0 ? $res[mt_rand(0, count($res) - 1)] : null;
    }
}