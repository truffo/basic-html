<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;

class LienTest extends \PHPUnit_Framework_TestCase
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
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#liens
     */
    public function testPresenceIntituleLien($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'a[href]');
        foreach ($nodes as $node) {
            $text = $node->getText();
            $alts = '';
            $imgs = $node->findAll('css', 'img');
            /** @var \Behat\Mink\Element\NodeElement $img */
            foreach ($imgs as $img) {
                $alts .= $img->getAttribute('alt');
            }
            $text = trim($text).trim($alts);

            if ($node->hasAttribute('title')) {
                $this->assertGreaterThanOrEqual(mb_strlen($text), mb_strlen(trim($node->getAttribute('title'))), $url.' Title pourri "'.$text.'" '.$node->getOuterHtml());
            } else {
                $this->assertGreaterThan(0, mb_strlen($text), $url.' Lien sans intitulé "'.$text.'" '.$node->getOuterHtml());
            }
        }
    }

    /**
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#liens
     */
    public function testLienIdentiquePointentVersLeMemeEndroit($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'a[href]');

        $tabText = [];
        foreach ($nodes as $node) {
            $src = $node->getAttribute('href');
            $text = $node->getText();
            $alts = '';
            $imgs = $node->findAll('css', 'img');
            /** @var \Behat\Mink\Element\NodeElement[] $imgs */
            foreach ($imgs as $img) {
                $alts .= $img->getAttribute('alt');
            }
            $text = trim($text).trim($alts);

            if (array_key_exists($text, $tabText)) {
                $this->assertEquals($tabText[$text], $src, $url.' lien "'.$text.'" vers plusieurs cibles différentes.');
            } else {
                $tabText[$text] = $src;
            }
        }
    }

    /**
     * @dataProvider urlProvider
     */
    public function testLienOntUneDestination($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'a[href]');
        foreach ($nodes as $node) {
            $href = $node->getAttribute('href');
            $this->assertNotEquals('#', $href, $url.' Détournement de balise lien pour en faire un bouton ou je suis en train de refiler la patate chaude aux suivants'.$node->getOuterHtml());
        }
    }
}
