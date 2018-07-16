<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// Example Test Case
// Precisa ver como utilizar os testes para as funcoes internas das classes
// Para testar os acessos aos routes pode usar o Runner do Postman -> "tenho exemplo disso para depois"

final class EmailTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Email::class,
            Email::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }
}