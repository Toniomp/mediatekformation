<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;

/**
 * Description of FormationRepositoryTest
 *
 * @author delah
 */
class FormationRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;        
    }
    
    /**
     * 
     * Création d'une instance de Formation
     * @return Formation
     */
    public function newFormation(): Formation{
        $formation = (new Formation())
                ->setTitle("PhpMyAdmin")
                ->setVideoId("123456789ab")
                ->setPublishedAt(new DateTime("2024-06-02"));
        return $formation;
    }
    
    public function testAddFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemoveFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->removeF($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    public function testFindAllOrderByFormation() {
        $repository = $this->recupRepository();
        $formationsAsc = $repository->findAllOrderBy('title', 'ASC');
        $this->assertEquals('Android Studio (complément n°1) : Navigation Drawer et Fragment', $formationsAsc[0]->getTitle(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals('Android Studio (complément n°10) : Ajout icone dans menu', $formationsAsc[1]->getTitle(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals('Android Studio (complément n°11) : Transformer une image en texte', $formationsAsc[2]->getTitle(), "erreur lors du tri par nom de l'affichage");
        
        $formationsDesc = $repository->findAllOrderBy('title', 'DESC');
        $this->assertEquals('UML : Diagramme de paquetages', $formationsDesc[0]->getTitle(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals('UML : Diagramme de classes', $formationsDesc[1]->getTitle(), "erreur lors du tri par nom de l'affichage");
        $this->assertEquals('UML : Diagramme de cas d\'utilisation', $formationsDesc[2]->getTitle(), "erreur lors du tri par nom de l'affichage");
    }
    
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $nbFormations = $repository->findByContainValue('publishedAt', '');
        $this->assertEquals($repository->count([]), count($nbFormations), "erreur de la méthode findByContainValue()");
        
        $formation = $repository->findByContainValue('title', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
        $this->assertEquals('Android Studio (complément n°1) : Navigation Drawer et Fragment', $formation[0]->getTitle(), "erreur de la méthode findByContainValue()");  
    }
    
    public function testFindAllLasted() {
        $repository = $this->recupRepository();
        $formations= $repository->findAllLasted(3);
        $this->assertEquals(3, count($formations), "erreur de la méthode findAllLasted() (nombre de formations renvoyés)");
        
        for ($i = 0; $i < 2; $i++){
            $this->assertGreaterThanOrEqual($formations[$i+1]->getPublishedAt(), $formations[$i]->getPublishedAt(), "erreur de la méthode findAllLasted() (tri incorrect)");
        }
    }
    
    public function testFindAllForOnePlaylist() {
        $repository = $this->recupRepository();
        $formations= $repository->findAllForOnePlaylist(12);
        $this->assertEquals(6, count($formations), "erreur de la méthode findAllForOnePlaylist() (nombre de formations renvoyés)");
    }
    
    
}
