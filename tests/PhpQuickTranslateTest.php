<?php

namespace MouseEatsCat;

use PHPUnit\Framework\TestCase;

class PhpQuickTranslateTest extends TestCase
{
    private $qt;

    public function setUp(): void
    {
        $this->qt = new PhpQuickTranslate();
    }

    public function testTranslation()
    {
        // Should return first
        $this->assertEquals('Hello', $this->qt->t('[:en]Hello'));
        $this->assertEquals('Hello', $this->qt->t([
            'en' => 'Hello'
        ]));
        $this->assertEquals('Hello', $this->qt->t([
            'Hello'
        ]));
        // Should return en
        $this->assertEquals('Hello', $this->qt->t('[:en]Hello[:fr]Bonjour'));
        $this->assertEquals('Hello', $this->qt->t([
            'en' => 'Hello',
            'fr' => 'Bonjour'
        ]));
        // Should return en
        $this->assertEquals('Hello', $this->qt->t('[:fr]Bonjour[:en]Hello'));
        $this->assertEquals('Hello', $this->qt->t([
            'fr' => 'Bonjour',
            'en' => 'Hello'
        ]));
        // Should return the entire string
        $this->assertEquals('HelloBonjour', $this->qt->t('HelloBonjour'));
        // Should return the first string
        $this->assertEquals('Hola', $this->qt->t('[:es]Hola[:fr]Bonjour'));
        $this->assertEquals('Hola', $this->qt->t('[:es]Hola[:fr]Bonjour'));
    }

    public function testTranslationMultiple()
    {
        // Should return en
        $this->assertEquals('Hello', $this->qt->t('[:es]Hola[:en]Hello[:fr]Bonjour'));
        $this->assertEquals('Hello', $this->qt->t([
            'es' => 'Hola',
            'en' => 'Hello',
            'fr' => 'Bonjour'
        ]));
        // Should return the first string
        $this->assertEquals('Hola', $this->qt->t('[:es]Hola[:de]Hallo[:fr]Bonjour'));
        $this->assertEquals('Hola', $this->qt->t([
            'es' => 'Hola',
            'de' => 'Hallo',
            'fr' => 'Bonjour'
        ]));
    }

    public function testTranslationChangeLang()
    {
        $this->assertEquals('Bonjour', $this->qt->setLang('fr')->t([
            'en' => 'Hello',
            'fr' => 'Bonjour'
        ]));

        $this->qt->setLang('es');

        $this->assertEquals('Hola', $this->qt->t([
            'fr' => 'Bonjour',
            'es' => 'Hola'
        ]));
    }

    public function testOutput()
    {
        $this->expectOutputString('Bonjour');

        $this->qt
            ->setLang('fr')
            ->et([
                'en' => 'Hello',
                'fr' => 'Bonjour'
            ]);
    }
}
