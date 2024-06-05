<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author delah
 */
class PlaylistsControllerTest extends WebTestCase    {
    
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);  
    }
    
    public function testContenuPageTri() {
        $client = static::createClient();
        
        // vérification de l'affichage trier par nom
        $trititleasc = $client->request('GET', '/playlists/tri/name/ASC');
        $trititleasc->assertSelectorTextContains('h5','Bases de la programmation (C#)');
        $trititledesc = $client->request('GET', '/playlists/tri/name/DESC');
        $trititledesc->assertSelectorTextContains('h5','Visual Studio 2019 et C#');
        
        // vérification de l'affichage trier par nombre de formations
        $triplaylistasc = $client->request('GET', '/playlists/tri/size/ASC');
        $triplaylistasc->assertSelectorTextContains('h5','Cours Informatique embarquée');
        $triplaylistdesc = $client->request('GET', '/playlists/tri/size/DESC');
        $triplaylistdesc->assertSelectorTextContains('h5','Bases de la programmation (C#)');
        
    }
    
    public function testFiltrePlaylist() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filter', ['recherche' => 'eclipse']);
        $this->asserCount(1, $crawler->filter('h5'));        
    }
    
    public function testLinkPlaylist() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink ('Bases de la programmation (C#)');
        $response = $client->getResponse();
        $response->assetEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $uri->assertEquals('/playlists/playlist/13', $uri);
    }
    
}
