<?php

namespace skh6075\dungeonplus\entity;

use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use skh6075\dungeonplus\DungeonData;
use skh6075\dungeonplus\DungeonPlus;

class DungeonEntity extends Monster{

    const NETWORK_ID = 10001;

    public $width = 0.25;
    public $height = 0.25;

    private static ?DungeonData $dungeonData;

    protected int $attack_queue = 0;

    protected int $targeting_queue = 0;

    protected int $lifeSpan = 0;

    public function __construct(Level $level, CompoundTag $nbt) {
        parent::__construct($level, $nbt);
    }

    public function getName(): string{
        return "DungeonEntity";
    }

    public function initEntity(): void{
        parent::initEntity();
        /** @var DungeonData $class */
        if (!($class = DungeonPlus::getInstance()->getMonsterData($this->namedtag->getString("dungeonName", ""))) instanceof DungeonData) {
            $this->close();
            return;
        }

        self::$dungeonData = $class;
        $this->setHealth($class->getHealth());
        $this->setScale($class->getScale());
    }

    public function saveNBT(): void{
        parent::saveNBT();
        $this->namedtag->setString("dungeonName", self::$dungeonData->getName());
    }

    public function getPlayerInRadius(): ?Player{
        /** @var null|Player $resultPlayer */
        $resultPlayer = $this->getLevel()->getNearestEntity($this, self::$dungeonData->getTargetingDistance(), Player::class);
        return $resultPlayer;
    }

    public function canAttackTargetPlayer(): bool{
        if (($player = $this->getTargetEntity()) instanceof Player) {
            return $this->distance($player) <= self::$dungeonData->getAttackDistance() and $this->targeting_queue >= self::$dungeonData->getAttackQueue();
        }

        return false;
    }

    public function onUpdate(int $currentTick): bool{
        $hasUpdated = parent::onUpdate($currentTick);
        if ($this->isClosed()) {
            return false;
        }

        if ($this->isAlive() and $this->getLevel() instanceof Level) {
            if ($this->attack_queue < self::$dungeonData->getAttackQueue()) {
                $this->attack_queue ++;
            }

            if ($this->lifeSpan < self::$dungeonData->getLifeSpanQueue()) {
                if (!$this->getTargetEntity() instanceof Player) {
                    $this->lifeSpan ++;
                    if ($this->lifeSpan >= self::$dungeonData->getLifeSpanQueue())
                        $this->flagForDespawn();
                } else {
                    $this->lifeSpan = 0;
                }
            }
            
            if (!$this->getTargetEntity() instanceof Player) {
                if (($targetPlayer = $this->getPlayerInRadius()) instanceof Player) {
                    $this->setTargetEntity($targetPlayer);
                }
            }

            /** @var Player $targetPlayer */
            if (($targetPlayer = $this->getTargetEntity()) instanceof Player) {
                if ($targetPlayer->getLevel() instanceof Level) {
                    if ($targetPlayer->getLevel()->getFolderName() === $this->getLevel()->getFolderName()) {
                        if ($this->distance($targetPlayer) > 6) {
                            parent::jump();
                        }

                        $this->followByWalking($targetPlayer);
                        if ($this->canAttackTargetPlayer()) {
                            $this->attack_queue = 0;
                            $targetPlayer->attack(new EntityDamageByEntityEvent($this, $targetPlayer, EntityDamageEvent::CAUSE_ENTITY_ATTACK, self::$dungeonData->getDamage()));
                        }
                    } else {
                        $this->setTargetEntity(null);
                    }
                } else {
                    $this->setTargetEntity(null);
                }
            }
        }

        return $hasUpdated;
    }

    public function followByWalking(Player $player): void{
        $xOffset = $player->x - $this->x;
        $yOffset = $player->y - $this->y;
        $zOffset = $player->z - $this->z;
        $xz_sq = $xOffset * $xOffset + $zOffset + $zOffset;
        $xz_modulus = sqrt($xz_sq);
        if ($xz_sq < 1.2) {
            $this->motion->x = 0;
            $this->motion->z = 0;
        } else {
            $speed_factor = 1.2;
            $this->motion->x = $speed_factor * ($xOffset / $xz_modulus);
            $this->motion->z = $speed_factor * ($zOffset / $xz_modulus);
        }

        if ($this->isUnderwater())
            if ($yOffset !== 0.0) {
                $this->motion->y = 0.1 * $yOffset;
            }

        $this->yaw = rad2deg(atan2(- $xOffset, $zOffset));
        $this->pitch = rad2deg(- atan2($yOffset, $xz_modulus));
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
    }
}