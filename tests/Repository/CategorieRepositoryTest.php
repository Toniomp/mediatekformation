<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author delah
 */
class CategorieRepositoryTest extends KernelTestCase {
    
    public function recupRepository(): CategorieRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;        
    }
    
    /**
     * 
     * Création d'une instance de Categorie
     * @return Categorie
     */
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                ->setName("PHP");
        return $categorie;
    }
    
    public function testAddCategorie() {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemoveCategorie() {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testFindAllForOnePlaylist() {
        $repository = $this->recupRepository();
        $categories= $repository->findAllForOnePlaylist(27);
        $this->assertEquals('Cours', $categories[0]->getName(), "erreur de la méthode findAllForOnePlaylist()");
        $this->assertEquals('POO', $categories[1]->getName(), "erreur de la méthode findAllForOnePlaylist()");
    }
}
