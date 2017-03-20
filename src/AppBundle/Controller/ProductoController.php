<?php
/**
 * Created by PhpStorm.
 * User: torres
 * Date: 20/03/17
 * Time: 16:55
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Producto;
use AppBundle\Form\ProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductoController extends Controller
{
    //preguntar por la vista form?

    /**
     * @Route (path="/", name="app_producto_indice")
     */
    public function IndexAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');
        $m->flush();
        $productos = $repo->findAll();
        return $this->render(':producto-templates:indice.html.twig',
            [
             //que es lo que hace el producto de abajo?
                'producto' => $productos
            ]);
    }


    /**
     * @Route (path="/Añadir",
     * name="app_producto_Añadir")
     * @Security("has_role('ROLE_USER')")
     */
    public function AñadirAction()
    {
        $Producto = new Producto();
        $form = $this->createForm(ProductoType::class, $Producto);
        return $this->render(':producto-templates:form.html.twig',
            [
                'form'  => $form->createView(),
                'action'  => $this->generateUrl('app_producto_Añadirlo'),
            ]);
    }

    /**
     * @Route (path="/Añadirlo",
     *      name="app_producto_Añadirlo")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function AñadirloAction(Request $request)
    {
        $Producto= new Producto();
        $form = $this->createForm(ProductoType::class, $Producto);
        $form->handleRequest($request);
        if($form->isValid()) {
            $user = $this->getUser();
            $Producto->setAuthor($user);
            $m = $this->getDoctrine()->getManager();
            $m->persist($Producto);
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }
        return $this->render(':producto-templates:form.html.twig',
            [
                'form'  => $form->createView(),
                'action'  => $this->generateUrl('app_producto_Añadirlo')
            ]);
    }

    /**
     * @Route (
     *     path="/Actualizar/{id}",
     *     name="app_producto_Actualizar"
     * )
     * @Security("has_role('ROLE_USER')")
     */
    public function ActualizarAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');
        $Producto = $repo->find($id);
        $form = $this->createForm(ProductoType::class, $Producto);
        return $this->render(':producto-templates:form.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_producto_Actualizarlo', ['id' => $id]),
            ]);
    }


    /**
     * @Route (
     *     path="/Actualizarlo/{id}",
     *     name="app_producto_Actualizarlo")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function ActualizarloAction($id, Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');
        $Producto = $repo->find($id);
        $form = $this->createForm(ProductoType::class, $Producto);
        $form->handleRequest($request);
        if($form->isValid()){
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }

        return $this->render(':producto-templates:form.html.twig',
            [
                'form' => $form->CreateView(),
                'action' => $this->generateUrl('app_producto_Actualizarlo', ['id' => $id]),
            ]);
    }
    //app_index_index? supongo que va a indice. Pero por que 2 index?


    /**
     * @Route (
     *     path="/Eliminar/{id}",
     *     name="app_producto_Eliminar"
     * )
     * @Security("has_role('ROLE_USER')")
     */
    public function EliminarAction($id)
    {
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Producto');
        $Producto = $repo->find($id);
        $m->remove($Producto);
        $m->flush();
        $this->addFlash('messages', 'Producto Eliminado');
        return $this->redirectToRoute('app_index_index');
    }

}