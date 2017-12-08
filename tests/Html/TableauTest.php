<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;

class TableauTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider urlProvider
     *
     * Tableau dans des tableau => forcément mise en forme non linéarisable => pas bien
     */
    public function testPasDeTableauxDansDesTableaux($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'table table');
        $this->assertCount(0, $nodes, $url.' mise en page par tableau.');
    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#cadres
     */
    public function testLesTableauxDeDonneOntUnCaption($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'table[summary]');
        foreach ($nodes as $node) {
            $this->assertCount(1, $node->findAll('css', 'caption'), $url.'un tableau de donnée à forcément un caption.');
        }
    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#test-5-7.2
     */
    public function testLesTableeauxDeDonneAssocieLesEntetesEtLesCellules($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'table[summary]');
        foreach ($nodes as $node) {
            $this->assertEquals(count($node->findAll('css', 'th')), count($node->findAll('css', 'th[scope]')), $url.' un tableau de données doit faire la liaison entre les en-tête et les données.');
        }
    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#test-5-8-1
     */
    public function testLesTableDeMiseEnPageUtiliseDesElementsDesTableauxDeDonnees($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'table:not([summary])');
        foreach ($nodes as $node) {
            $this->assertEquals(0, count($node->findAll('css', 'th')), $url.' un tableau de mise en forme ne doit pas utilisé de th');
            $this->assertEquals(0, count($node->findAll('css', 'thead')), $url.' un tableau de mise en forme ne doit pas utilisé de thead');
            $this->assertEquals(0, count($node->findAll('css', 'tfoot')), $url.' un tableau de mise en forme ne doit pas utilisé de tfoot');
            $this->assertEquals(0, count($node->findAll('css', 'td[scope]')), $url.' un tableau de mise en forme ne doit pas utilisé de td[scope]');
            $this->assertEquals(0, count($node->findAll('css', 'td[headers]')), $url.' un tableau de mise en forme ne doit pas utilisé de td[headers]');
            $this->assertEquals(0, count($node->findAll('css', 'td[colgroup]')), $url.' un tableau de mise en forme ne doit pas utilisé de td[colgroup]');
            $this->assertEquals(0, count($node->findAll('css', 'td[axis]')), $url.' un tableau de mise en forme ne doit pas utilisé de td[axis]');
        }
    }
}
