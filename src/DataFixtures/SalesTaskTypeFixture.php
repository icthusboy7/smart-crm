<?php

namespace App\DataFixtures;

use App\Entity\ComercialTaskType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SalesTaskTypeFixture
 */
class SalesTaskTypeFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $columnMapFunction = [
            'description' => 'setDescription',
            'is_special'  => 'setIsSpecial',
            'form'        => 'setForm',
            'color'       => 'setColor',
            'icon'        => 'setIcon',
        ];
        /** @var array $content */
        $content = [
            [
                'description' => 'tarea simple',
                'is_special'  => 0,
                'form'        => '[]',
                'color'       => '99CCFF',
                'icon'        => null,
            ],
            [
                'description' => 'Petición de cotización',
                'is_special'  => 1,
                'form'        => '[{\"type\":\"text\",\"required\":true,\"label\":\"Numero cotizacion\",\"className\":\"numCoti\",\"name\":\"numCoti\",\"subtype\":\"text\"},{\"type\":\"date\",\"required\":true,\"label\":\"Date Field\",\"className\":\"fechaCoti\",\"name\":\"fechaCoti\"}]',
                'color'       => 'FFFFFF',
                'icon'        => 'fab fa-wpforms',
            ],
            [
                'description' => 'Tarea de auto',
                'is_special'  => 1,
                'form'        => '[{\"type\":\"date\",\"label\":\"Date Field\",\"className\":\"form-control\",\"name\":\"date-1565005657384\"},{\"type\":\"file\",\"label\":\"File Upload\",\"className\":\"form-control\",\"name\":\"file-1565005663729\",\"subtype\":\"file\"},{\"type\":\"button\",\"label\":\"Button\",\"subtype\":\"button\",\"className\":\"btn-default btn\",\"name\":\"button-1565005660498\",\"style\":\"default\"}]',
                'color'       => 'FFDA8F',
                'icon'        => 'fas fa-car',
            ],
            [
                'description' => 'Tarea especial 2',
                'is_special'  => 1,
                'form'        => '[{\"type\":\"header\",\"subtype\":\"h1\",\"label\":\"Especial2\"},{\"type\":\"text\",\"label\":\"Text Field\",\"className\":\"form-control\",\"name\":\"text-1566807375413\",\"subtype\":\"text\"},{\"type\":\"text\",\"label\":\"Text Field\",\"className\":\"form-control\",\"name\":\"text-1566807381247\",\"subtype\":\"text\"},{\"type\":\"text\",\"label\":\"Text Field\",\"className\":\"form-control\",\"name\":\"text-1566807388611\",\"subtype\":\"text\"},{\"type\":\"select\",\"label\":\"Select\",\"className\":\"form-control\",\"name\":\"select-1566807394613\",\"values\":[{\"label\":\"Option 1\",\"value\":\"option-1\",\"selected\":true},{\"label\":\"Option 2\",\"value\":\"option-2\"},{\"label\":\"Option 3\",\"value\":\"option-3\"}]}]',
                'color'       => 'FF7C36',
                'icon'        => null,
            ],
        ];
        $count   = 0;
        foreach ($content as $key => $row) {
            $count++;
            $content = new ComercialTaskType();
            foreach ($row as $column => $value) {
                if (!isset($columnMapFunction[$column])) {
                    continue;
                }
                $content->{$columnMapFunction[$column]}($value);
            }
            $manager->persist($content);
            $this->addReference(ComercialTaskType::class.'_'.$count, $content);
        }

        $manager->flush();
    }
    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return [
            'dev',
            'pre',
        ];
    }
}
