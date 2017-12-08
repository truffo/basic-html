<?php
namespace Tests\Html;

use Tests\CsvFileIterator;
use Tests\MinkTestCaseTrait;

class StructureTest extends \PHPUnit_Framework_TestCase
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
     * Un h1 est présent et unique.
     *
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#crit-9-1
     */
    public function testLaPageAUnH1Unique($url)
    {
        $page = $this->visit($url);
        $nodes = $page->findAll('css', 'h1');

        $this->assertCount(1, $nodes, $url.'H1 multiple ou h1 manquant');
    }

    /**
     * Un h1 ne peut pas être contenu dans une section.
     *
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#crit-9-1
     */
    public function testLeH1NEstPasDansUneSection($url)
    {
        $page = $this->visit($url);

        $this->assertCount(0, $page->findAll('css', 'article > h1'), $url.' le h1 est positionée dans une section de type article.');
        $this->assertCount(0, $page->findAll('css', 'section > h1'), $url.' le h1 est positionée dans une section de type section.');
        $this->assertCount(0, $page->findAll('css', 'nav > h1'), $url.' le h1 est positionée dans une section de type nav.');
        $this->assertCount(0, $page->findAll('css', 'aside > h1'), $url.' le h1 est positionée dans une section de type aside.');
    }

    /**
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#crit-9-1
     */
    public function testLeH1NEstAvantLesAutresTitres($url)
    {
        $page = $this->visit($url);
        $html = $page->getHtml();

        $posH1 = mb_strpos($html, '<h1');
        $posH2 = mb_strpos($html, '<h2');
        $posH3 = mb_strpos($html, '<h3');
        $posH4 = mb_strpos($html, '<h4');
        $posH5 = mb_strpos($html, '<h5');
        $posH6 = mb_strpos($html, '<h6');

        if (($posH1 > $posH2) && ($posH1 > $posH3) && ($posH1 > $posH4) && ($posH1 > $posH5) && ($posH1 > $posH6)) {
            $this->assertFalse(true, $url.' Le titre h1 est après un autre titre !');
        }
    }

    /**
     * Le h1 doit faire moins de 100 caractères.
     *
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link https://static.googleusercontent.com/media/www.google.fr/fr/fr/intl/fr/webmasters/docs/search-engine-optimization-starter-guide-fr.pdf
     */
    public function testLeChampEstSuffisementCourt($url)
    {
        $page = $this->visit($url);

        $node = $page->find('css', 'h1');
        $this->assertLessThanOrEqual(100, mb_strlen($node->getText()), $url.' le h1 fait plus de 100 caractères.');
    }

    /**
     * @group seo
     *
     * @dataProvider urlProvider
     *
     * @link http://www.accessiweb.org/index.php/accessiweb-html5aria-liste-deployee.html#crit-9-2
     */
    public function testLaBaliseMainEstPresenteEtUnique($url)
    {
        $page = $this->visit($url);
        /** @var \Behat\Mink\Element\NodeElement[] $nodes */
        $nodes = $page->findAll('css', 'main');
        $this->assertCount(1, $nodes, $url.' balise main manquante.');
    }

    /**
     * @group seo
     * @group w3c
     *
     * @dataProvider urlProvider
     */
    public function testW3C($url)
    {
        $acceptableErrorList = [
            "Bad value noopenner for attribute rel on element a",
            "Element hx:include not allowed as child of element div in this context"
        ];
        $tmp = file_get_contents($url);
        $path = 'tmp'.DIRECTORY_SEPARATOR.time().'_'.md5($url);
        $pathXml = 'tmp'.DIRECTORY_SEPARATOR.time().'_'.md5($url).'_w3c.xml';

        file_put_contents($path, $tmp);
        $cmd = 'java -Xss2048k -jar ./node_modules/vnu-jar/build/dist/vnu.jar --format xml "'.$path.'" 2>&1';
        $result = shell_exec($cmd);
        $crawler = new Symfony\Component\DomCrawler\Crawler();
        $crawler->addXmlContent($result);
        file_put_contents($pathXml, $result);
        $errors = $crawler->filter('messages > error');
        $filteredError = [];
        foreach ($errors as $error) {
            $trimTextContent = trim($error->textContent);
            $acceptableError = false;

            foreach ($acceptableErrorList as $acceptableErrorMessage) {
                if (mb_strpos($trimTextContent, $acceptableErrorMessage) !== false) {
                    $acceptableError = true;
                }
            }
            if (!$acceptableError) {
                $filteredError[] = $error;
            }
        }
        $this->assertCount(0, $filteredError, 'La page '.$url.' comportent '.count($filteredError).' erreurs HTML non acceptable.');
    }
}
