<?php


class FormTest extends \PHPUnit_Framework_TestCase
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
    public function testFormulaireAUneAction($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'form');
        foreach ($nodes as $node) {
            $this->assertTrue($node->hasAttribute('method'), $url.'Un formulaire a une method '.$node->getOuterHtml());
            $this->assertTrue($node->hasAttribute('action'), $url.'Un formulaire a une action '.$node->getOuterHtml());
            $this->assertGreaterThan(0, mb_strlen(trim($node->getAttribute('action'))), "\nUn formulaire a une action qui n'est pas vide \n".$node->getOuterHtml());
            $this->assertNotEquals('#', $node->getAttribute('action'), $url.'Un # n\'est pas super précis '.$node->getOuterHtml());
        }
    }

    /**
     * @dataProvider urlProvider
     */
    public function testFormulaireAUnBoutonDeSoumission($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'form');
        foreach ($nodes as $node) {
            $btn1 = $node->findAll('css', 'button[type=submit]');
            $btn2 = $node->findAll('css', 'input[type=submit]');
            $btn3 = $node->findAll('css', 'input[type=image]');
            $btn4 = $node->findAll('css', 'button[type=image]');
            $this->assertGreaterThanOrEqual(1, count($btn1) + count($btn2) + count($btn3) + count($btn4), $url."\n un formulaire a au moins un bouton de validation. \n".$node->getOuterHtml());
        }
    }

    /**
     * @dataProvider urlProvider
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/button
     */
    public function testLesBoutonsOntUnNom($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'button');
        foreach ($nodes as $node) {
            $this->assertTrue($node->hasAttribute('name'), $url.' un bouton a forcément un name '.$node->getOuterHtml());
        }
    }
}
