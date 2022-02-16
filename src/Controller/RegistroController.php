<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Usuario;
use App\Entity\Canjeo;

use App\Entity\Premio;
use Symfony\Component\Security\Core\Security;

class RegistroController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/registro", name="registro")
     */
    public function index(): Response
    {
        $repositorio=$this->getDoctrine()->getRepository(Canjeo::class);
        $user = $this->security->getUser();
        $id = $user->GetId();

        
        $Canjeo=$repositorio->findBy(array('usuario_id'=>$id));

        
        return $this->render('registro/lista_registros.html.twig', array('premios'=>$Canjeo));

    }
}
