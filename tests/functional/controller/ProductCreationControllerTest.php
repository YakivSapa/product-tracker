<?php

declare(strict_types=1);
namespace App\Tests\Functional\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Tests\AbstractDatabaseTestCase;

class ProductCreationControllerTest extends AbstractDatabaseTestCase
{
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        /** @var ProductRepository $productRepository */
        $productRepository = $this->entityManager->getRepository(Product::class);
        $this->productRepository = $productRepository;
    }
    public function testSubmitValidFormCreatesProductInDatabase(): void
    {
        $this->assertCount(0, $this->productRepository->findAll());
        // $this->assertTrue(true);

        $this->client->request('GET', '/product/new');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
            'product[name]' => 'Test Product',
            'product[description]' => 'This is a test product.',
            'product[size]' => 20,
        ]);

        $this->assertResponseRedirects();
        $product = $this->productRepository->findOneBy(['name' => 'Test Product']);
        $this->assertNotNull($product);
        $this->assertSame('This is a test product.', $product->getDescription());
        $this->assertSame(20, $product->getSize());
    }

    public function testSubmitInvalidFormShowsValidationErrors(): void
    {
        $this->client->request('GET', '/product/new');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
            'product[name]' => '',
            'product[description]' => '',
            'product[size]' => -5,
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('#product_name_error1', 'This value should not be blank.');
        $this->assertSelectorTextContains('#product_size_error1', 'This value should be either positive or zero.');
    }
}