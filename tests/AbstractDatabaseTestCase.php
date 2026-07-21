<?php

namespace App\Tests\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class AbstractDatabaseTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;
    
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        if ('test' !== $kernel->getEnvironment()) {
            throw new LogicException('Execution is possible only in Test environment.');
        }
        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    private function initDatabase(KernelInterface $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadata);
    }
}
