<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;

class ConsultationTest extends \PHPUnit_Framework_TestCase
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
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#crit-13-2
     */
    public function testIndicationNouvelleFenetrePourLesLiensExternes($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'a[href]');
        foreach ($nodes as $node) {
            if ($node->hasAttribute('target')) {
                $target = $node->getAttribute('target');
                if ($target === '_blank') {
                    $img = $node->find('css', 'img');

                    if (count($img) === 0) {
                        $this->assertTrue($node->hasAttribute('title'), $url.' : Target blank implique un title indiquant la présence de la nouvelle fenêtre'.$node->getOuterHtml());
                        $title = $node->getAttribute('title');

                        $this->assertStringEndsWith('(nouvelle fenêtre)', mb_strtolower($title),  $url.' : Target blank title'.$node->getOuterHtml());
                    } else {
                        $this->assertTrue($img->hasAttribute('alt'), $url.' : Target blank sur un image lien implique un title indiquant la présence de la nouvelle fenêtre'.$node->getOuterHtml());
                        $title = $img->getAttribute('alt');
                        $this->assertStringEndsWith('(nouvelle fenêtre)', mb_strtolower($title),  $url.' : Target blank image lien alt'.$node->getOuterHtml());
                    }
                }
            }
        }
    }
}
