<?php


class CadreTest extends \PHPUnit_Framework_TestCase
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
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#cadres
     */
    public function testPresenceTitleSurLesIframe($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'iframe');
        foreach ($nodes as $node) {
            $this->assertTrue($node->hasAttribute('title'), $url.' : Title manquant sur iframe '.$node->getOuterHtml());
            $this->assertGreaterThan(0, mb_strlen(trim($node->getAttribute('title'))), $url.' : Title vide sur iframe '.$node->getOuterHtml());
        }
    }
}
