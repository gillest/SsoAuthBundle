<?php

namespace BeSimple\SsoAuthBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use BeSimple\SsoAuthBundle\Tests\AppKernel;
use BeSimple\SsoAuthBundle\Tests\HttpClient;

/**
 * @method assertEquals
 */
abstract class WebTestCase extends BaseWebTestCase
{
    static protected $tmpPath;
    static protected $configFile;

    const LOGIN_USER    = 'user';
    const LOGIN_ADMIN   = 'admin';
    const LOGIN_INVALID = 'invalid';

    public function provideClients()
    {
        $clients = array();
        $names   = array('cas');

        foreach ($names as $name) {
            $clients[] = array(static::createClient(array('sso_server_name' => $name)));
        }

        return $clients;
    }

    protected function getXml(Crawler $crawler)
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        $crawler->each(function(\DOMElement $node) use ($document) {
            $document->appendChild($document->importNode($node, true));
        });
        return html_entity_decode($document->saveXML());
    }

    static protected function createKernel(array $options)
    {
        static::$tmpPath    = sys_get_temp_dir().'/be_simple_sso_auth_bundle_tests';
        static::$configFile = __DIR__.'/../Resources/config/'.$options['sso_server_name'].'.yml';

        if (file_exists(static::$tmpPath)) {
            $fs = new Filesystem();
            $fs->remove(static::$tmpPath);
        }

        $kernel = new AppKernel(
            static::$tmpPath,
            static::$configFile,
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );

        HttpClient::setKernel($kernel);

        return $kernel;
    }
}
