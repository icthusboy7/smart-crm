<?php

namespace App\DataFixtures;

use App\Entity\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DocumentTypeFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $count          = 0;
        $columnFunction = [
            'CODE'        => 'setCode',
            'description' => 'setDescription',
        ];

        $documentType = [
            ['CODE' => '7001', 'description' => 'ESTADOS FINANCIEROS'],
            ['CODE' => '7006', 'description' => 'IMPUESTO DE SOCIEDADES (MOD.200)'],
            ['CODE' => '7003', 'description' => 'IVA ANUAL'],
            ['CODE' => '7002', 'description' => 'IVA TRIMESTRAL'],
            ['CODE' => '7004', 'description' => 'IRPF TRIMESTRAL'],
            ['CODE' => '7005', 'description' => 'IRPF ANUAL '],
            ['CODE' => '', 'description' => ''], //empty value
            ['CODE' => '7005', 'description' => 'MOD. 347'],
            ['CODE' => '7012', 'description' => 'PODERES'],
            ['CODE' => '9003', 'description' => 'ESCRITURA CONSTITUCIÃ“N'],
            ['CODE' => '7007', 'description' => 'CERT. CORRIENTE PAGO SS '],
            ['CODE' => '7008', 'description' => 'CERT. CORRIENTE PAGO AEAT'],
            ['CODE' => '1', 'description'    => 'CONTRATO'],
            ['CODE' => '3', 'description'    => 'SEPA'],
            ['CODE' => '36', 'description'   => 'ORDEN DE PAGO'],
            ['CODE' => '2', 'description'    => 'CEI'],
            ['CODE' => '9002', 'description' => 'DNI APODERADO'],
            ['CODE' => 'ANOTHER', 'description' => 'OTRO'],
        ];
        foreach ($documentType as $key => $row) {
            $count++;
            $content = new DocumentType();
            foreach ($row as $column => $value) {
                $content->{$columnFunction[$column]}($value);
            }
            $manager->persist($content);
            $this->addReference(DocumentType::class.'_'.$count, $content);
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
        // TODO: Implement getGroups() method.
        return [
            'dev',
            'pre',
        ];
    }
}
