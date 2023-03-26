<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups as Group;

#[ORM\Entity(repositoryClass: FichierRepository::class)]
#[Table(name: 'param_fichier')]
#[ORM\HasLifecycleCallbacks]
class Fichier
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(["fichier"])]
    protected $id;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column(length: 255)]
    #[Group(["fichier"])]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Group(["fichier"])]
    private ?string $alt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 5)]
    #[Group(["fichier"])]
    private ?string $url = null;


    #[Assert\NotNull(message:"Veuillez sélectionner un fichier", groups:["FileRequired"])]
    private  $file ;


    /**
     * @var string
     */
    private $uploadDir;


    /**
     * @var mixed
     */
    private $tempFilename;


    private $filePrefix = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function __construct()
    {

        $this->date = new \DateTime();


    }

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {


        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->alt && $file) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->alt;

            // On réinitialise les valeurs des attributs url et alt
            $this->url = null;
            $this->alt = null;
            $this->size = 0;
        }
    }





    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function preUpload()
    {

        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            //dump('foo00');exit;
            return false;
        }

        //dump('foo');exit;

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        //$this->url = $this->file->guessExtension();
        $clientOriginalName = $this->file->getClientOriginalName();

        $fileExt  = pathinfo($clientOriginalName, PATHINFO_EXTENSION);
        $baseName = $this->filePrefix.'_'.pathinfo($clientOriginalName, PATHINFO_FILENAME);

        $this->url = $fileExt;

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->alt = substr(str_slug($baseName, '_'), 0, 255 - 1 - strlen($fileExt)) . '.' . $fileExt;
        $this->size      = $this->file->getSize();

    }


    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function upload()
    {
        $uploadDir = $this->getUploadRootDir();
        // Si jamais il n'y a pas de fichier (champ facultatif)t
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $file = $this->getFullFileName();

            if (file_exists($file)) {
                unlink($file);
            }
        }


        //dump($this->alt, $this->getFullPath());exit;

        $this->file->move(
            $this->getFullPath(), // Le répertoire de destination
            $this->alt
        );

        // On déplace le fichier envoyé dans le répertoire de notre choix

    }


    #[ORM\PreRemove()]
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getFullFileName();
    }

    #[ORM\PreRemove()]
    public function removeUpload()
    {
        if ($this->getUrl() == 'link') {
            return;
        }
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    /**
     * @param $uploadDir
     * @return mixed
     */
    public function setUploadDir($uploadDir)
    {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $this->uploadDir = $uploadDir;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return $this->uploadDir ?: 'uploads';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../public/' . $this->getUploadDir();
    }

    protected function getUploadBaseDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../public/uploads/';
    }

    public function getWebPath()
    {
        return @$this->getFullFileName();
    }



    public function getFileNamePath()
    {
        return 'uploads/'.$this->getPath().'/'.$this->getFileName();
    }

    /**
     * @return mixed
     */
    public function getFullPath()
    {
        return $this->getUploadBaseDir() . $this->getPath();
    }

    /**
     * @return mixed
     */
    public function getFullFileName()
    {

        if ($this->getUrl() != 'link') {
            $fileName = file_exists($this->getFullPath() . '/' . $this->alt) ?
                $this->getFullPath() . '/' . $this->alt :
                $this->getFullPath() . '/' . $this->id . '.' . $this->url;
        } else {
            $fileName = $this->getAlt();
        }

        return $fileName;
    }

    public function getFileName()
    {
        return basename($this->getFullFileName());
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }


    /**
     * Get the value of filePrefix
     */
    public function getFilePrefix()
    {
        return $this->filePrefix;
    }

    /**
     * Set the value of filePrefix
     *
     * @return  self
     */
    public function setFilePrefix($filePrefix)
    {
        $this->filePrefix = $filePrefix;

        return $this;
    }
}
