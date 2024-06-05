<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author delah
 */
class PlaylistRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;        
    }
    
    /**
     * 
     * Création d'une instance de Playlist
     * @return Playlist
     */
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName("Cours de PHP");
        return $playlist;
    }
    
    public function testAddPlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemovePlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->removeP($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    public function testFindAllOrderByName() {
        $repository = $this->recupRepository();
        $playlistsAsc = $repository->findAllOrderByName('ASC');
        $this->assertEquals(13, $playlistsAsc[0]->getId(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals(8, $playlistsAsc[1]->getId(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals(17, $playlistsAsc[2]->getId(), "erreur lors du tri par nom de l'affichage");
        
        $playlistsDesc = $repository->findAllOrderByName('DESC');
        $this->assertEquals(2, $playlistsDesc[0]->getId(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals(11, $playlistsDesc[1]->getId(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals(4, $playlistsDesc[2]->getId(), "erreur lors du tri par nom de l'affichage");
    }
    
    public function testFindAllOrderBySize() {
        $repository = $this->recupRepository();
        $playlistsAsc = $repository->findAllOrderBySize('ASC');
        $this->assertEquals(22, $playlistsAsc[0]->getId(), "erreur lors du tri par contenu de l'affichage");
        $this->assertEquals(25, $playlistsAsc[1]->getId(), "erreur lors du tri par contenu de l'affichage");
        $this->assertEquals(26, $playlistsAsc[2]->getId(), "erreur lors du tri par contenu de l'affichage");
        
        $playlistsDesc = $repository->findAllOrderBySize('DESC');
        $this->assertEquals(13, $playlistsDesc[0]->getId(), "erreur lors du tri par contenu de l'affichage");
        $this->assertEquals(3, $playlistsDesc[1]->getId(), "erreur lors du tri par contenu de l'affichage");
        $this->assertEquals(7, $playlistsDesc[2]->getId(), "erreur lors du tri par contenu de l'affichage");
    }
    
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->findByContainValue('name', '');
        $this->assertEquals($repository->count([]), count($nbPlaylists), "erreur de la méthode findByContainValue()");
        
        $playlists = $repository->findByContainValue('name', 'Cours Curseurs');
        $this->assertEquals('Cours Curseurs', $playlists[0]->getName(), "erreur de la méthode findByContainValue()");  
    }
    
    public function testFindOneByName(){
        $repository = $this->recupRepository();
        $playlist = $repository->findOneByName('Cours Curseurs');
        $this->assertEquals('Cours Curseurs', $playlist->getName(), "erreur de la méthode findOneByName()");
    }
}
