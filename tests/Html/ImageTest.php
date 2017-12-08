<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;


/**
 * Class ImageTest.
 */
class ImageTest extends \PHPUnit_Framework_TestCase
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
     * @see http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#images
     */
    public function testChaqueImagePossedeAttributAlt($url)
    {
        $page = $this->visit($url);

        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'img');
        foreach ($nodes as $node) {
            $this->assertNotNull($node->getAttribute('alt'), $url.' Attribut alt manquant '.$node->getOuterHtml());
        }
    }
    /**
     * @dataProvider urlProvider
     *
     * @see http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#images
     */
    public function testChaqueImagePossedeLesAttributsSrcWidthHeigh($url)
    {
        $page = $this->visit($url);

        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'img');
        foreach ($nodes as $node) {
            $this->assertNotNull($node->getAttribute('src'), $url.' Attribut src manquant '.$node->getOuterHtml());
            $this->assertNotNull($node->getAttribute('width'), $url.' Attribut width manquant '.$node->getOuterHtml());
            $this->assertNotNull($node->getAttribute('height'), $url.' Attribut height manquant '.$node->getOuterHtml());
        }
    }

    /**
     * @dataProvider urlProvider
     */
    public function testAreaPossedeUnAlt($url)
    {
        $page = $this->visit($url);

        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'area');
        foreach ($nodes as $node) {
            $this->assertNotNull($node->getAttribute('alt'), $url.' Attribut alt manquant (area) '.$node->getOuterHtml());
        }
    }

    /**
     * @dataProvider urlProvider
     */
    public function testAreaPossedeUnAttributCoords($url)
    {
        $page = $this->visit($url);

        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'area');
        foreach ($nodes as $node) {
            $this->assertNotNull($node->getAttribute('coord'), $url.' Attribut coord manquant (img area) '.$node->getOuterHtml());
        }
    }

//    /**
//     * @dataProvider urlProvider
//     *
//     * Optimiser temps de chargement de la page
//     */
//    public function testTailleReelImage($url)
//    {
//        $page = $this->visit($url);
//        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
//        $nodes = $page->findAll('css', 'img');
//        foreach ($nodes as $node) {
//            $src = $node->getAttribute('src');
//            $width = $node->getAttribute('width');
//            $height = $node->getAttribute('height');
//            list($realWidth, $realHeight, $mimeType) = getimagesize($src);
//            $this->assertEquals($realWidth, $width, "Le width de l'image n'est pas bon" . $node->getOuterHtml());
//            $this->assertEquals($realHeight, $height, "Le height de l'image n'est pas bon" . $node->getOuterHtml());
//        }
//        $this->assertTrue(true, 'Non applicable');
//    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/glossaire-du-referentiel-accessiweb-html5aria.html#mTitreLien
     */
    public function testCoherenceEntreLeAltEtLeTitleSurLesImages($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'img');
        foreach ($nodes as $node) {
            if ($node->hasAttribute('title')) {
                $this->assertNotNull($node->getAttribute('alt'), $url.' Une image a forcément un attribut alt'.$node->getOuterHtml());
                $this->assertEquals($node->getAttribute('alt'), $node->getAttribute('title'), $url." Le title doit être égal au alt s'il est présent ".$node->getOuterHtml());
            }
        }
    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/glossaire-du-referentiel-accessiweb-html5aria.html#mTitreLien
     */
    public function testCoherenceEntreLeAltEtLeTitleSurLesButton($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'button[type=image]');
        foreach ($nodes as $node) {
            if ($node->hasAttribute('title')) {
                $this->assertNotNull($node->getAttribute('alt'),  $url.' Une bouton image a forcément un attribut alt'.$node->getOuterHtml());
                $this->assertEquals($node->getAttribute('alt'), $node->getAttribute('title'),  $url." Le title doit être égal au alt s'il est présent ".$node->getOuterHtml());
            }
        }
    }

    /**
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/glossaire-du-referentiel-accessiweb-html5aria.html#mTitreLien
     */
    public function testCoherenceAltTitleArea($url)
    {
        $page = $this->visit($url);

        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'area');
        foreach ($nodes as $node) {
            if ($node->hasAttribute('title')) {
                $this->assertNotNull($node->getAttribute('alt'), $url.' Un area a forcément un attribut alt'.$node->getOuterHtml());
                $this->assertEquals($node->getAttribute('alt'), $node->getAttribute('title'),  $url." Le title doit être égal au alt s'il est présent ".$node->getOuterHtml());
            }
        }
    }
}
