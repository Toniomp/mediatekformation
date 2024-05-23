<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategorieType;


class AdminCategoriesController extends AbstractController {
    
     /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    
    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response{
        return $this->renderPage( $request);
    }
    
    private function renderPage(Request $request): Response {
        $categorieRepository = $this->categorieRepository;
        $newcategorie = new Categorie();

        $formCategorie = $this->createForm(CategorieType::class, $newcategorie);
        
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $name = $newcategorie->getName();
            $existingCategorie = $this->categorieRepository->findOneBy(['name' => $name]);
        
            if ($existingCategorie) {
                $this->addFlash('error', 'Ce nom de catégorie existe déjà');
            } else {
                $this->categorieRepository->add($newcategorie, true);
                return $this->redirectToRoute('admin.categories');
            }
        }
        $categories = $categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
    
    /**
     * @Route("/admin/delete/{id}", name="admin.categorie.delete")
     * @param Categorie $categorie
     * @return Response
     */    
    public function delete(Categorie $categorie): Response {
        if (count($categorie->getFormations()) === 0) {
            $this->categorieRepository->remove($categorie, true);
            return $this->redirectToRoute('admin.categories');
        }
        else {
            return $this->redirectToRoute('admin.categories');
        }
    }   
       
}
