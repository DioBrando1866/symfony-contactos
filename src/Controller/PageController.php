<?php

namespace App\Controller;

use App\Entity\Contacto;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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

    #[Route('/', name: 'indice')]
    public function indice(): Response
    {
        return $this->render('inicio.html.twig');
    }

    // Si queremos validar un parámetro, su usa 'requeriments' que es una expresión regular. En este caso, solo permite números de longitud variable

    #[Route('/contacto/{codigo}', name: 'contacto', requirements: ['codigo' => '[0-9]+'])]

    // Symfony inyecta la dependencia ManagerRegistry automáticamente

    // Le pasa la variable $codigo con el valor en {codigo}. Si no se le pasa, coge 1 por defecto, en otro caso, daría not found

    public function ficha(ManagerRegistry $doctrine, int $codigo = 1): Response
    {

        // La primera instrucción suele ser esta, ya que cogemos el repositorio de la entidad asociada

        $repositorio = $doctrine->getRepository(Contacto::class);

        // Ahora usamos uno de los métodos del repositorio

        $contacto = $repositorio->find($codigo);

        // Y creamos la vista HTML

        $html = "
        <h1>Detalle del contacto</h1>
        <p>Nombre: " . $contacto->getNombre() . "</p>
        <p>Teléfono: " . $contacto->getTelefono() . "</p>
        <p>Email: " . $contacto->getEmail() . "</p>
        ";

        return new Response($html);

    }
}

