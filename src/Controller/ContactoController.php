<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Provincia;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactoFormType;
final class ContactoController extends AbstractController
{
    #[Route('/contacto/nuevo', name: 'nuevo-con-datos')]
    public function nuevo(ManagerRegistry $doctrine, Request $request)
    {
        $contacto = new Contacto();
        $formulario = $this->createForm(ContactoFormType::class, $contacto);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $contacto = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contacto);
            $entityManager->flush();
            return $this->redirectToRoute('indice');
        }
        return $this->render('nuevo.html.twig', array('formulario' => $formulario->createView()));
    }

    #[Route("/contacto/editar/{codigo}", name: "editar")]
    public function editar(ManagerRegistry $doctrine, Request $request, int $codigo)
    {
        $repositorio = $doctrine->getRepository(Contacto::class);
        //En este caso, los datos los obtenemos del repositorio de contactos
        $contacto = $repositorio->find($codigo);
        if ($contacto) {
            // A partir de $contacto, rellena automáticamente el formulario y el resto es igual que para nuevo
            $formulario = $this->createForm(ContactoFormType::class, $contacto);

            $formulario->handleRequest($request);

            if ($formulario->isSubmitted() && $formulario->isValid()) {
                // Guardamos y redirigimos a la ficha
                $contacto = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($contacto);
                $entityManager->flush();
                return $this->redirectToRoute('indice');
            }
            // Ponemos los datos del contacto
            return $this->render('editar.html.twig', array(
                'formulario' => $formulario->createView()
            ));
        } else {
            return $this->render('contacto.html.twig', [
                'contacto' => NULL
            ]);
        }
    }



    #[Route("/contacto/borrar/{codigo}", name: "borrar")]
    public function borrar(
        ManagerRegistry $doctrine,
        int $codigo
    ) {
        $contacto = $doctrine->getRepository(Contacto::class)->find($codigo);
        if ($contacto) {
            $entityManager = $doctrine->getManager();
            try {
                $entityManager->remove($contacto);
                $entityManager->flush();
                return $this->redirectToRoute("indice");

            } catch (\Exception $e) {
                throw $this->createNotFoundException("Error al borrar el contacto");
            }
        } else {
            throw $this->createNotFoundException("No se ha encontrado el contacto");
        }
    }
}

