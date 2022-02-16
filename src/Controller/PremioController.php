<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Premio;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Security\Core\Security;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PremioController extends AbstractController
{
    /**
     * @Route("/premio", name="premio")
     */
    public function index(): Response
    {
        return $this->render('premio/index.html.twig', [
            'controller_name' => 'PremioController',
        ]);
    }



    //---------------------------------------pruebas

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }





    //------------------------------------------------NUEVO PREMIO--------------------------
    /**
     * @Route("/gestor/premio/nuevo", name="nuevo_premio")
     */
    public function nuevo_premio(Request $request, SluggerInterface $slugger)
    {
        $premio = new premio();

        $formulario = $this->createFormBuilder($premio)
            ->add("cabecera", TextType::class)
            ->add("descripcion", TextType::class)
            ->add("valor", TextType::class)
            ->add("ndisponibles", TextType::class)
            #->add("fecha", DateType::class)
            #->add("usuario_id", TextType::class)
            ->add("foto", FileType::class, ['mapped' => false,'required' => false])
            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();

            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){

                //----------------------------------------------CARGANDO FOTO-----------------------
                $brochureFile = $formulario->get('foto')->getData();


                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('fotos_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new \Exception("algo ha ido mal con la carga de la foto");
                    }
    
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $premio->setFoto($newFilename);
                }








                //-----------------------------------------------------------------------------------
                $premio = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                //$premio->SetRol('ROLE_USER');
                //$premio->SetPuntos('0');
                #$premio->SetPremio_id('1');
                #$premio->SetFecha(18/11/2021);
                $premio->SetFecha();

                $user = $this->security->getUser();
                $premio->SetUsuarioId($user);
                $entityManager->persist($premio);
                $entityManager->flush();
                return $this->redirectToRoute('premios');
            }
            return$this->render('premio/nuevo_premio.html.twig',array('formulario'=>$formulario->createView()));
    }



    //-------------------------------lista premios

    /**
     * @Route("/lista/premios", name="premios")
     */
    public function premios()
    {
        $repositorio=$this->getDoctrine()->getRepository(Premio::class);
        $premios=$repositorio->findAll();
        return $this->render('premio/lista_premios.html.twig', array('premios'=>$premios));
    }



    /**
     * @Route("/admin/eliminarPremio/{id}", name="eliminarPremio")
     */
    public function eliminarPremio($id){
        $repositorioPremio=$this->getDoctrine()->getRepository(Premio::class);
        $premio=$repositorioPremio->find($id);

        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($premio);
            $entityManager->flush();

        return $this->redirectToRoute('premios');

            
    }


    //------------------------------editar premio------------------------------------
        /**
        * @Route("/admin/premio/editar/{id}", name="editarpremio")
        */
        public function editarPremio(Request $request, $id){
            $repositorio=$this->getDoctrine()->getRepository(Premio::class);
            $premio=$repositorio->find($id);

            $formulario = $this->createFormBuilder($premio)
                ->add("cabecera", TextType::class)
                ->add("descripcion", TextType::class)
                ->add("valor", TextType::class)
                ->add("ndisponibles", TextType::class)
            
                ->add("save", SubmitType::class, array("label"=>"enviar"))
                ->getForm();

                $formulario->handleRequest($request);

                if($formulario->isSubmitted()&&$formulario->isValid()){
                    $premio = $formulario->getData();
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($premio);
                    $entityManager->flush();
                    return $this->premios();
                }
                return$this->render('premio/nuevo_premio.html.twig',array('formulario'=>$formulario->createView()));
        }

}
