<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\{TextType,TelType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

use App\Entity\Proveedor;
use App\Repository\ProveedorRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/proveedores", name="view_proveedores_route")
     */
    public function viewPostAction(ProveedorRepository $proveedorRepository): Response
    {
        $proveedores = $proveedorRepository
            ->findAll();
        return $this->render("pages/index.html.twig", ['proveedores' => $proveedores]);
    }

    /**
     * @Route("/proveedores/create", name="create_proveedores_route")
     */
    public function createPostAction(Request $request, PersistenceManagerRegistry $doctrine){
        $proveedor = new Proveedor;
        $form = $this->createFormBuilder($proveedor)
        ->add('nombre', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('email', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('telefono', TelType::Class, array('attr' => array('class' =>'form-control')))
        ->add('tipoProveedor', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('activo', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('add', SubmitType::Class, array('label' => 'Añade un nuevo proveedor', 'attr' => array('class' =>'form-control btn btn-danger')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $nombre = $form['nombre']->getData();
            $email = $form['email']->getData();
            $telefono = $form['telefono']->getData();
            $tipoProveedor = $form['tipoProveedor']->getData();
            $activo = $form['activo']->getData();

            $proveedor->setNombre($nombre);
            $proveedor->setEmail($email);
            $proveedor->setTelefono($telefono);
            $proveedor->setTipoProveedor($tipoProveedor);
            $proveedor->setActivo($activo);

            $em = $doctrine->getManager();
            $em->persist($proveedor);
            $em->flush();
            $this->addFlash('message', 'Proveedor añadido correctamente');
            return $this->redirectToRoute('view_proveedores_route');
        }
        return $this->render("pages/create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/proveedores/update/{id}", name="update_proveedores_route")
     */
    public function updatePostAction($id, Request $request, PersistenceManagerRegistry $doctrine){
        $proveedor = new Proveedor;

        $form = $this->createFormBuilder($proveedor)
        ->add('nombre', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('email', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('telefono', TelType::Class, array('attr' => array('class' =>'form-control')))
        ->add('tipoProveedor', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('activo', TextType::Class, array('attr' => array('class' =>'form-control')))
        ->add('add', SubmitType::Class, array('label' => 'Edita este proveedor', 'attr' => array('class' =>'form-control btn btn-danger')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $nombre = $form['nombre']->getData();
            $email = $form['email']->getData();
            $telefono = $form['telefono']->getData();
            $tipoProveedor = $form['tipoProveedor']->getData();
            $activo = $form['activo']->getData();

            $em = $doctrine->getManager();
            $proveedor=$doctrine->getRepository(Proveedor::class)->find($id);

            $proveedor->setNombre($nombre);
            $proveedor->setEmail($email);
            $proveedor->setTelefono($telefono);
            $proveedor->setTipoProveedor($tipoProveedor);
            $proveedor->setActivo($activo);

            $em->flush();
            $this->addFlash('message', 'Proveedor editado correctamente');
            return $this->redirectToRoute('view_proveedores_route');
        }
        return $this->render("pages/update.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/proveedores/delete/{id}", name="delete_proveedores_route")
     */
    public function deletePostAction($id, Request $request, PersistenceManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $proveedor=$doctrine->getRepository(Proveedor::class)->find($id);
        $em->remove($proveedor);
        $em->flush();
        $this->addFlash('message', 'Proveedor borrado correctamente');
        return $this->redirectToRoute('view_proveedores_route');
        return $this->render("pages/delete.html.twig");
    }
}
