<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="playlist")
     */
    private $formations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }
    
    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setPlaylist($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation) && $formation->getPlaylist() === $this){
            // set the owning side to null (unless already changed)
            $formation->setPlaylist(null);
        }

        return $this;
    }

	/**
	 * @return Collection<int, string>
	 */	
    public function getCategoriesPlaylist() : Collection
	{
            $categories = new ArrayCollection();
            foreach($this->formations as $formation){
		$categoriesFormation = $formation->getCategories();
		foreach($categoriesFormation as $categorieFormation){
                    if(!$categories->contains($categorieFormation->getName())){
                	$categories[] = $categorieFormation->getName();
                    }
                }
            }
            return $categories;
	}

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }
    
    public function updateSize(): void 
    {
        $this->size = $this->formations->count();
    }
    
    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    
    public function validate(ExecutionContextInterface $context)
    {
        $name = $this->getName();
        if ($name == "")
        {
            $context->buildViolation("ce champ doit être rempli")
                ->atPath('name')
                ->addViolation();
        }
    }

}
