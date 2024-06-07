<?php

namespace MouseEatsCat;

use PHPUnit\Framework\TestCase;
use MouseEatsCat\QuickTranslate;

class QuickTranslateTest extends TestCase
{
    private QuickTranslate $qt;
    /** @var array */
    private array $languages;
    private string $resourceDir = __DIR__ . '/resources';

    public function setUp(): void
    {
        $this->qt = new QuickTranslate();
        $this->languages = [
            'en' => 'English',
            'fr' => 'French',
        ];
    }

    public function testSources(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            // multilingual
            $this->qt->clearTranslations()->addTranslationSource("$this->resourceDir/multilingual/multilingual.json");
            $this->testLanguages($this->languages, $i);

            $this->qt->clearTranslations()->addTranslationSource("$this->resourceDir/multilingual/");
            $this->testLanguages($this->languages, $i);

            // Single-language
            $this->qt->clearTranslations()->addTranslationSource("$this->resourceDir/single/");
            $this->testLanguages($this->languages, $i);

            $this->qt->clearTranslations()
                ->addTranslationSource("$this->resourceDir/single/en.json", 'en')
                ->addTranslationSource("$this->resourceDir/single/fr.json", 'fr');
            $this->testLanguages($this->languages, $i);
        }
    }

    public function testArrays(): void
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
        $translationCount = count($translations);
        for ($i = 1; $i <= $translationCount; $i++) {
            $this->testLanguages($this->languages, $i);
        }

        // Test single-language
        $this->qt->clearTranslations()
            ->addTranslations($translationsEn, 'en')
            ->addTranslations($translationsFr, 'fr');

        $translationCount = count($translations);
        for ($i = 1; $i <= $translationCount; $i++) {
            $this->testLanguages($this->languages, $i);
        }
    }

    private function testLanguages($languages, $translation_index): void
    {
        foreach ($languages as $lang_key => $lang_val) {
            $this->assertEquals(
                "$lang_val Translation $translation_index",
                $this->qt->setLanguage($lang_key)->t("translation$translation_index")
            );
        }
    }

    public function testTranslation(): void
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

    public function testTranslationMultiple(): void
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

    public function testTranslationChangeLang(): void
    {
        $this->assertEquals('Bonjour', $this->qt->setLanguage('fr')->t([
            'en' => 'Hello',
            'fr' => 'Bonjour'
        ]));

        $this->qt->setLanguage('es');

        $this->assertEquals('Hola', $this->qt->t([
            'fr' => 'Bonjour',
            'es' => 'Hola'
        ]));
    }

    public function testOutput(): void
    {
        $this->expectOutputString('Bonjour');

        $this->qt
            ->setLanguage('fr')
            ->et([
                'en' => 'Hello',
                'fr' => 'Bonjour'
            ]);
    }
}
