<?php

namespace MouseEatsCat;

use PHPUnit\Framework\TestCase;
use MouseEatsCat\PhpQuickTranslate;

class PhpQuickTranslateTest extends TestCase
{
    /** @var PhpQuickTranslate */
    private $qt;

    /** @var array */
    private $langs;

    private $resourceDir = __DIR__ . '/resources';

    public function setUp(): void
    {
        $this->qt = new PhpQuickTranslate();
        $this->langs = [
            'en' => 'English',
            'fr' => 'French',
        ];
    }

    public function testSources()
    {
        for ($i = 1; $i <= 2; $i++) {
            // multilingual
            $this->qt->clearTranslations()->addTranslationSource("{$this->resourceDir}/multilingual/multilingual.json");
            $this->testLangs($this->langs, $i);

            $this->qt->clearTranslations()->addTranslationSource("{$this->resourceDir}/multilingual/");
            $this->testLangs($this->langs, $i);

            // Single-language
            $this->qt->clearTranslations()->addTranslationSource("{$this->resourceDir}/single/");
            $this->testLangs($this->langs, $i);

            $this->qt->clearTranslations()
                ->addTranslationSource("{$this->resourceDir}/single/en.json", 'en')
                ->addTranslationSource("{$this->resourceDir}/single/fr.json", 'fr');
            $this->testLangs($this->langs, $i);
        }
    }

    public function testArrays()
    {
        $translations = [
            "translation1" => [
                "en" => "English Translation 1",
                "fr" => "French Translation 1"
            ],
            "translation2" => [
                "en" => "English Translation 2",
                "fr" => "French Translation 2"
            ],
        ];
        $translationsEn = [
            "translation1" => $translations['translation1']['en'],
            "translation2" => $translations['translation2']['en'],
        ];
        $translationsFr = [
            "translation1" => $translations['translation1']['fr'],
            "translation2" => $translations['translation2']['fr'],
        ];

        // Test multilingual
        $this->qt->clearTranslations()->addTranslations($translations);
        for ($i = 1; $i <= count($translations); $i++) {
            $this->testLangs($this->langs, $i);
        }

        // Test single-language
        $this->qt->clearTranslations()
            ->addTranslations($translationsEn, 'en')
            ->addTranslations($translationsFr, 'fr');
        for ($i = 1; $i <= count($translations); $i++) {
            $this->testLangs($this->langs, $i);
        }
    }

    private function testLangs($langs, $translation_index)
    {
        foreach ($langs as $lang_key => $lang_val) {
            $this->assertEquals(
                "$lang_val Translation $translation_index",
                $this->qt->setLang($lang_key)->t("translation$translation_index")
            );
        }
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
