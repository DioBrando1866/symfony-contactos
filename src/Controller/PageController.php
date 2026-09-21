<?php

namespace App\Controller;

use App\Entity\Contacto;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
final class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PageController.php',
        ]);
    }


    #[Route('/', name: 'indice', requirements: ['codigo' => '[0-9]+'])]

    public function ficha(ManagerRegistry $doctrine, int $codigo = 1): Response
    {
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contactos = $repositorio->findAll();
        return $this->render('inicio.html.twig', [
            'contactos' => $contactos
        ]);
    }

    #[Route('/contacto/nuevo/{nombre}/{telefono}/{email}', name: 'nuevo-con-datos')]
    public function nuevoConDatos(
        ManagerRegistry $doctrine,
        Request $request,
        string $nombre,
        string $telefono,
        string $email
    ) {
        $contacto = new Contacto();

        $contacto->setNombre($nombre);
        $contacto->setTelefono($telefono);
        $contacto->setEmail($email);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($contacto);
        $entityManager->flush();
        return $this->redirectToRoute('indice');
    }

    #[Route("/contacto/update/{codigo}/{nombre_nuevo}/{telefono_nuevo}/{email_nuevo}", name: "update")]
    public function update(
        ManagerRegistry $doctrine,
        int $codigo,
        string $nombre_nuevo,
        string $telefono_nuevo,
        string $email_nuevo

    ) {
        $contacto = $doctrine->getRepository(Contacto::class)->find($codigo);
        if ($contacto) {
            $contacto->setNombre($nombre_nuevo);
            $contacto->setTelefono($telefono_nuevo);
            $contacto->setEmail($email_nuevo);
            $entityManager = $doctrine->getManager();
            try {
                $entityManager->persist($contacto);
                $entityManager->flush();
                return $this->redirectToRoute("indice");
            } catch (\Exception $e) {
                throw $this->createNotFoundException("Error al actualizar el contacto");
            }
        } else {
            throw $this->createNotFoundException("No se ha encontrado el contacto");
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

