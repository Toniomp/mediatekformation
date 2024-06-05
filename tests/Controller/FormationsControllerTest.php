<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of FormationsControllerTest
 *
 * @author delah
 */
class FormationsControllerTest extends WebTestCase {
    
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);  
    }
    
    public function testContenuPageTri() {
        $client = static::createClient();
        
        // vérification de l'affichage trier par nom
        $trititleasc = $client->request('GET', 'formations/tri/title/ASC');
        $trititleasc->assertSelectorTextContains('h5','Android Studio (complément n°1) : Navigation Drawer et Fragment');
        $trititledesc = $client->request('GET', 'formations/tri/title/DESC');
        $trititledesc->assertSelectorTextContains('h5','UML : Diagramme de paquetages');
        
        // vérification de l'affichage trier par playlist
        $triplaylistasc = $client->request('GET', '/tri/name/ASC/playlist');
        $triplaylistasc->assertSelectorTextContains('h5','Bases de la programmation n°74 - POO : collections');
        $triplaylistdesc = $client->request('GET', '/tri/name/DESC/playlist');
        $triplaylistdesc->assertSelectorTextContains('h5','C# : ListBox en couleur');
        
        // vérification de l'affichage trier par date
        $tridateasc = $client->request('GET', '/formations/tri/publishedAt/ASC');
        $tridateasc->assertSelectorTextContains('h5','Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
        $tridatedesc = $client->request('GET', '/formations/tri/publishedAt/DESC');
        $tridatedesc->assertSelectorTextContains('h5','Eclipse n°8 : Déploiement');
    }
    
    public function testFiltreFormation() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filter', ['recherche' => 'eclipse']);
        $this->asserCount(9, $crawler->filter('h5'));        
    }
    
    public function testLinkFormation() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink ('Eclipse n°8 : Déploiement');
        $response = $client->getResponse();
        $response->assetEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $uri->assertEquals('/formations/formation/1', $uri);
    }
    
}
