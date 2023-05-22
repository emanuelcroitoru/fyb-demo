<?php

namespace App\Factory;

use App\Entity\ProjectMilestones;
use App\Repository\ProjectMilestonesRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProjectMilestones>
 *
 * @method        ProjectMilestones|Proxy create(array|callable $attributes = [])
 * @method static ProjectMilestones|Proxy createOne(array $attributes = [])
 * @method static ProjectMilestones|Proxy find(object|array|mixed $criteria)
 * @method static ProjectMilestones|Proxy findOrCreate(array $attributes)
 * @method static ProjectMilestones|Proxy first(string $sortedField = 'id')
 * @method static ProjectMilestones|Proxy last(string $sortedField = 'id')
 * @method static ProjectMilestones|Proxy random(array $attributes = [])
 * @method static ProjectMilestones|Proxy randomOrCreate(array $attributes = [])
 * @method static ProjectMilestonesRepository|RepositoryProxy repository()
 * @method static ProjectMilestones[]|Proxy[] all()
 * @method static ProjectMilestones[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ProjectMilestones[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static ProjectMilestones[]|Proxy[] findBy(array $attributes)
 * @method static ProjectMilestones[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ProjectMilestones[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ProjectMilestonesFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'description' => self::faker()->realTextBetween(50, 100),
            'milestoneDeadline' => self::faker()->dateTime(),
            'title' => self::faker()->realTextBetween(30, 50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ProjectMilestones $projectMilestones): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProjectMilestones::class;
    }
}
