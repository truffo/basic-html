<?php


class NavigationTest extends \PHPUnit_Framework_TestCase
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
     */
    public function testLienEvitementSontPresent($url)
    {
        $page = $this->visit($url);
        $this->assertNotNull($page->findLink('Aller au menu'), $url.'Manque le lien "Aller au menu"');
        $this->assertNotNull($page->findLink('Aller au contenu'), $url.'Manque le lien "Aller au contenu"');
    }

    /**
     * @dataProvider urlProvider
     */
    public function testGroupeLienImportantPossedeUnIdentifiant($url)
    {
        $page = $this->visit($url);
        /* @var \Behat\Mink\Element\NodeElement[] $nodes */
        $this->assertCount(count($page->findAll('css', 'article')), $page->findAll('css', 'article[id]'), $url.'Une balise article ne possède pas d\'identifiant');
        $this->assertCount(count($page->findAll('css', 'nav')), $page->findAll('css', 'nav[id]'), $url.'Une balise nav ne possède pas d\'identifiant');
        $this->assertCount(count($page->findAll('css', 'section')), $page->findAll('css', 'section[id]'), $url.'Une balise section ne possède pas d\'identifiant');
        $this->assertCount(count($page->findAll('css', 'main')), $page->findAll('css', 'main[id]'), $url.'Une balise main ne possède pas d\'identifiant');
    }
}
