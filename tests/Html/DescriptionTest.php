<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;
use Behat\Mink\Element\NodeElement;

class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    use MinkTestCaseTrait;

    /**
     * @return CsvFileIterator
     */
    public function urlProvider()
    {
        return new CsvFileIterator(__DIR__.'/../../data/urls.csv');
    }

    /**
     * La meta description doit être présente.
     *
     * @group seo
     *
     * @dataProvider urlProvider
     */
    public function testUniciteDescription($url)
    {
        $page = $this->visit($url);
        $nodes = $page->findAll('css', 'meta');
        $count = 0;
        /** @var NodeElement $node */
        foreach ($nodes as $node) {
            if ($node->hasAttribute('name') && $node->getAttribute('name') === 'description') {
                ++$count;
            }
        }
        $this->assertEquals(1, $count, $url.' Description multiple ou description manquante ');
    }

    /**
     * La meta description doit avoir une taille comprise entre 80 et 300 caractères.
     *
     * @group seo
     *
     * @dataProvider urlProvider
     */
    public function testTailleDescription($url)
    {
        $page = $this->visit($url);
        $nodes = $page->findAll('css', 'meta');
        $description = '';
        /** @var NodeElement $node */
        foreach ($nodes as $node) {
            if ($node->hasAttribute('name') && $node->getAttribute('name') === 'description') {
                $description = $node->getAttribute('content');
            }
        }
        $this->assertGreaterThanOrEqual(80, mb_strlen($description), $url.' Description inférieur à 80 caractères.');
        $this->assertLessThanOrEqual(300, mb_strlen($description), $url.' Description inférieur à 300 caractères.');
    }
}
