<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;
//use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UsuarioController extends AbstractController
{

    

    //------------------------------------------------NUEVO USUARIO--------------------------
    /**
     * @Route("/usuario/nuevo", name="nuevo_usuario")
     */
    public function nuevo_usuario(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $usuario = new Usuario();

        $formulario = $this->createFormBuilder($usuario)
            ->add("dni", TextType::class)
            ->add("nombre", TextType::class)
            ->add("apellidos", TextType::class)
            ->add("pass", PasswordType::class)
            ->add("email", EmailType::class)
            ->add("telefono", NumberType::class)
            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();

            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){
                $usuario = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $usuario->SetRol('ROLE_USER');
                $usuario->SetPuntos('0');
                $usuario->setPassword($passwordEncoder->encodePassword($usuario, $formulario['pass']->getData()));
                $entityManager->persist($usuario);
                $entityManager->flush();
                return $this->redirectToRoute("premios");
            }
            return$this->render('usuario/nuevo_usuario.html.twig',array('formulario'=>$formulario->createView()));
    }



    //------------------------------------------------NUEVO USUARIO DESDE ADMIN--------------------------
    /**
     * @Route("/admin/usuario/nuevo", name="nuevo_usuario_desde_admin")
     */
    public function nuevo_usuario_desde_admin(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $usuario = new Usuario();

        $formulario = $this->createFormBuilder($usuario)
            ->add("dni", TextType::class)
            ->add("nombre", TextType::class)
            ->add("apellidos", TextType::class)
            ->add("pass", PasswordType::class)
            ->add("email", EmailType::class)
            ->add("telefono", TextType::class)
            ->add("Rol", TextType::class)

            
            ->add('Rol', ChoiceType::class, array(
                'choices' => array(
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                    'gestor' => 'ROLE_GESTOR'
                )
            ))
            ->add("puntos", TextType::class)

            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();

            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){
                $usuario = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $usuario->setPassword($passwordEncoder->encodePassword($usuario, $formulario['pass']->getData()));
                //$usuario->SetRol('ROLE_USER');
                //$usuario->SetPuntos('0');
                $entityManager->persist($usuario);
                $entityManager->flush();
                return $this->redirectToRoute("premios");
            }
            return$this->render('usuario/nuevo_admin.html.twig',array('formulario'=>$formulario->createView()));
    }



    
    
    
    
 

    
    //------------------------------editar usuario------------------------------------
    /**
    * @Route("/admin/usuario/editar/{id}", name="editarusuario")
    */
    public function editarusuario2(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder){
        $repositorio=$this->getDoctrine()->getRepository(Usuario::class);
        $usuario=$repositorio->find($id);

        $formulario = $this->createFormBuilder($usuario)
            ->add("dni", TextType::class)
            ->add("nombre", TextType::class)
            ->add("apellidos", TextType::class)
            //->add("pass", PasswordType::class)
            ->add("email", EmailType::class)
            ->add("telefono", TextType::class)
            ->add("Rol", TextType::class)

            
            ->add('Rol', ChoiceType::class, array(
                'choices' => array(
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                    'gestor' => 'ROLE_GESTOR'
                )
            ))
            ->add("puntos", TextType::class)
            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();

            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){
                $usuario = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                //$usuario->setPassword($passwordEncoder->encodePassword($usuario, $formulario['pass']->getData()));
                $entityManager->persist($usuario);
                $entityManager->flush();
                return $this->usuarios();
            }
            return $this->render('usuario/editar_usuario.html.twig',array('formulario'=>$formulario->createView()));
    }


    //-------------------------------lista usuarios

    /**
    * @Route("/lista/usuarios", name="usuarios")
    */
    public function usuarios()
    {
        $repositorio=$this->getDoctrine()->getRepository(Usuario::class);
        $usuarios=$repositorio->findAll();
        return $this->render('usuario/lista_usuarios.html.twig', array('usuarios'=>$usuarios));
    }






    //------------------------------puntos dni------------------------------------
    /**
    * @Route("/gestor/usuario/puntosdni/{dni}", name="editarusuario3")
    */
    public function editarusuariodni(Request $request, $dni){
        $repositorio=$this->getDoctrine()->getRepository(Usuario::class);
        

        $usuario=$repositorio->findOneBy(array('dni'=>$dni));
        
        $formulario = $this->createFormBuilder($usuario)
            
            ->add("puntos", TextType::class)
            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();
            
            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){
                $usuario = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                
                $entityManager->persist($usuario);
                $entityManager->flush();
                return $this->redirectToRoute("buscar");
            }
            return $this->render('usuario/puntos.html.twig',array('formulario'=>$formulario->createView()));
    }


    //----------------------------------------buscar usuario desde formulario-----------------------
    /**
    * @Route("/gestor/buscar", name="buscar")
    */
    public function buscar(Request $request){
        $dniIntroducido=$request->request->get('dni');


        if($request->server->get('REQUEST_METHOD')=='POST'){
            return $this->redirectToRoute('editarusuario3', ["dni" => $dniIntroducido]);
        }
        else{
            
            
            return $this->render('usuario/buscar_usuario_puntos.html.twig');
        }

        
    }

    
    
    //------------------------------editar contraseña------------------------------------
    /**
    * @Route("/usuario/cambiarpass/{id}", name="editarpass")
    */
    public function editarpass(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder){
        $repositorio=$this->getDoctrine()->getRepository(Usuario::class);
        $usuario=$repositorio->find($id);

        $formulario = $this->createFormBuilder($usuario)
            
            ->add("pass", PasswordType::class)
            
            
            ->add("save", SubmitType::class, array("label"=>"enviar"))
            ->getForm();

            $formulario->handleRequest($request);

            if($formulario->isSubmitted()&&$formulario->isValid()){
                $usuario = $formulario->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $usuario->setPassword($passwordEncoder->encodePassword($usuario, $formulario['pass']->getData()));
                $entityManager->persist($usuario);
                $entityManager->flush();
                return $this->redirectToRoute('logout');
            }
            return $this->render('usuario/editar_pass.html.twig',array('formulario'=>$formulario->createView()));
    }


    

    //-------------------------------eliminar usuario--------------------------------
    /**
    * @Route("/admin/eliminarUsuario/{id}", name="eliminarUsuario")
    */
    public function eliminarUsuario($id){
        $repositorioUsuarios=$this->getDoctrine()->getRepository(Usuario::class);
        $usuario=$repositorioUsuarios->find($id);

        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('usuarios');

    }
}


?>