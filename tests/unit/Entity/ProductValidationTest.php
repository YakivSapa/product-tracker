<?php

namespace App\Tests\Unit\Entity;

use App\Tests\AbstractUnitTestCase;
use App\Entity\Product;

class ProductValidationTest extends AbstractUnitTestCase
{
    public function testValidProductPasses(): void
    {
        $product = new Product();
        $product->setName('Valid Product Name');
        $product->setSize(10);

        $this->assertEntityValid($product);
    }

    public function testInvalidProductFails(): void
    {
        $product = new Product();
        $product->setName(''); // Invalid: name is blank
        $product->setSize(-5); // Invalid: size is negative

        $this->assertEntityInvalid($product, 2);
    }

    public function testNameCannotBeBlank(): void
    {
        $product = new Product();
        $product->setName(''); // Invalid: name is blank
        $product->setSize(10);

        $this->assertPropertyHasViolation($product, 'name');
    }

    public function testSizeMustBePositiveOrZero(): void
    {
        $product = new Product();
        $product->setName('Valid Name');
        $product->setSize(-1); // Invalid: size is negative

        $this->assertPropertyHasViolation($product, 'size');
    }
}