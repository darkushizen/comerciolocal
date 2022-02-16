<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Usuario;
use App\Entity\Canjeo;


use App\Entity\Premio;
use Symfony\Component\Security\Core\Security;

class CanjeoController extends AbstractController
{
    //---------------------------------------pruebas

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function debug_to_console( $data, $context = 'Debug in console'){
        ob_start();

        $output = 'console.info(\''.$context.':\');';
        $output .= 'console.log(' . json_encode($data).');';
        $output = sprintf('<script>$s</script>', $output);
        echo $output;
    }





    //------------------------------canjear------------------------------------
    /*
    @Ro("/canjear/{valor},{cabecera},{descripcion},{id},{idpremio}", name="canjear")
    */






    //------------------------------canjear------------------------------------
    /**
    * @Route("/canjear/valor={valor}&cabecera={cabecera}&descripcion={descripcion}&id={id}&idpremio={idpremio}", name="canjear")
    */
    public function canjear(Request $request, $valor, $cabecera, $descripcion, $id, $idpremio){
        //-------------comprobando que hay suficientes puntos para canjear------
        $repositorio=$this->getDoctrine()->getRepository(Usuario::class);
        $usuario=$repositorio->find($id);
        $puntos=$usuario->GetPuntos();
        if($puntos>=$valor){


        //-------------añadiendo el canjeo a la tabla de canjeos de la BD-------
        $canjeo = new Canjeo();

        $entityManager = $this->getDoctrine()->getManager();

        
        $canjeo->SetValor($valor);
        $canjeo->SetCabecera($cabecera);
        $canjeo->SetDescripcion($descripcion);


        $canjeo->SetFecha();
        $user = $this->security->getUser();
        $canjeo->SetUsuarioId($user);

        $entityManager->persist($canjeo);
        $entityManager->flush();

        //--------------restando los puntos del usuario-----------------

        $puntos = ($puntos - $valor);

        $usuario->SetPuntos($puntos);

        $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();

        
        //-------------restando stock al premio------------------------

        
        $repositorioPremio=$this->getDoctrine()->getRepository(Premio::class);
        $premio=$repositorioPremio->find($idpremio);
        
        $stock = $premio->getNdisponibles();
        $premio->SetNdisponibles($stock-1);

        $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($premio);
                $entityManager->flush();
        
        //------------eliminando premio en caso de que el stock se acabe---------------
        $repositorioPremio2=$this->getDoctrine()->getRepository(Premio::class);
        $premio2=$repositorioPremio->find($idpremio);
        $stock2 = $premio2->getNdisponibles();

        if($stock2 <= 0){
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($premio2);
                $entityManager->flush();

        }
        

        return $this->redirectToRoute("premios");

        }
        else{
            return $this->render('premio/sin_puntos.html.twig');
        }
        
            
    }   

        
}
