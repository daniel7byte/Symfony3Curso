<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class PruebasController extends Controller
{

    public function indexAction(Request $request, $name, $page)
    {
        //var_dump($request->query->get("hola")); //GET
        //var_dump($request->request->get("holapost")); //POST
        //die();
        // replace this example code with whatever you need

        $users = [
            ['name' => 'Jose', 'old' => 15],
            ['name' => 'Andres', 'old' => 10],
            ['name' => 'Carlos', 'old' => 27]
        ];

        $colores = ['azul' => 'mi favorito', 'verde' => 'vomito'];
        return $this->render('AppBundle:Pruebas:index.html.twig', [
            'texto' => 'Hola '.$name.' '.$page,
            'users' => $users,
            'colores' => $colores

        ]);
    }
}
