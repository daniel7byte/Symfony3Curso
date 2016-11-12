<?php

namespace MiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MiBundle\Form\CursoType;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{
    public function indexAction()
    {
      $this->get('session')->set("sesion_prueba", "Hola");
      $this->get('session')->remove("sesion_prueba");
      return $this->render('MiBundle:Default:index.html.twig', [
        'session' => $this->get('session')->get("sesion_prueba")
      ]);
    }

    public function createAction()
    {
      $curso = new \MiBundle\Entity\Curso();

      $curso->setTitulo('Este es un titulo default');
      $curso->setDescripcion('Este curso es muy bueno');
      $curso->setPrecio(80.000);

      $em = $this->getDoctrine()->getManager();
      $em->persist($curso);
      $flush = $em->flush();

      if($flush != null){
        echo "No se creo el curso";
      }else{
        echo "Curso creado correctamente";
      }

      die();
    }

    public function readAction()
    {
      $em = $this->getDoctrine()->getManager();
      $cursosRepo = $em->getRepository("MiBundle:Curso");
      // $cursos = $cursosRepo->findAll();

      $cursos = $cursosRepo->findBy(['precio'=>80]);

      foreach ($cursos as $curso) {
        echo $curso->getId().'<br>';
        echo $curso->getTitulo().'<br>';
        echo $curso->getDescripcion().'<br>';
        echo $curso->getPrecio().'<br>';
        echo "<hr>";
      }

      die();
    }

    public function updateAction($id, $titulo, $descripcion, $precio)
    {
      $em = $this->getDoctrine()->getManager();
      $cursosRepo = $em->getRepository("MiBundle:Curso");
      $cursos = $cursosRepo->find($id);

      $cursos->setTitulo($titulo);
      $cursos->setDescripcion($descripcion);
      $cursos->setPrecio($precio);

      $em->persist($cursos);
      $flush = $em->flush();

      if($flush != null){
        echo "No se actualizó el curso";
      }else{
        echo "Curso actualizado correctamente";
      }

      die();
    }

    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $cursosRepo = $em->getRepository("MiBundle:Curso");
      $cursos = $cursosRepo->find($id);
      $em->remove($cursos);
      $flush = $em->flush();

      if($flush != null){
        echo "NO! Se eliminó " . $id;
      }else{
        echo "Se eliminó " . $id;
      }

      die();
    }

    public function nativeSQLAction()
    {
      $em = $this->getDoctrine()->getManager();
      $db = $em->getConnection();
      $query = "SELECT * FROM cursos";
      $stmt = $db->prepare($query);
      $params = [];
      $stmt->execute($params);

      $cursos = $stmt->fetchAll();

      foreach ($cursos as $curso) {
        echo $curso['id'].'<br>';
        echo $curso['titulo'].'<br>';
        echo $curso['descripcion'].'<br>';
        echo $curso['precio'].'<br>';
        echo "<hr>";
      }

      die();
    }

    public function DQLAction()
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery("
        SELECT c FROM MiBundle:Curso c
        WHERE c.precio > :precio
      ")->setParameter("precio", "80");
      $cursos = $query->getResult();

      foreach ($cursos as $curso) {
        echo $curso->getId().'<br>';
        echo $curso->getTitulo().'<br>';
        echo $curso->getDescripcion().'<br>';
        echo $curso->getPrecio().'<br>';
        echo "<hr>";
      }

      die();
    }

    public function QueryBuilderAction()
    {
      $em = $this->getDoctrine()->getManager();
      $cursosRepo = $em->getRepository("MiBundle:Curso");

      $query = $cursosRepo->createQueryBuilder("c")
                ->where("c.precio > :precio")
                ->setParameter("precio", "85")
                ->getQuery();
      $cursos = $query->getResult();

      foreach ($cursos as $curso) {
        echo $curso->getId().'<br>';
        echo $curso->getTitulo().'<br>';
        echo $curso->getDescripcion().'<br>';
        echo $curso->getPrecio().'<br>';
        echo "<hr>";
      }

      die();
    }

    public function QueryRepoAction()
    {
      $em = $this->getDoctrine()->getManager();
      $cursosRepo = $em->getRepository("MiBundle:Curso");
      $cursos = $cursosRepo->getCursos();
      foreach ($cursos as $curso) {
        echo $curso->getId().'<br>';
        echo $curso->getTitulo().'<br>';
        echo $curso->getDescripcion().'<br>';
        echo $curso->getPrecio().'<br>';
        echo "<hr>";
      }

      die();
    }

    public function FormAction(Request $request)
    {
      $curso = new \MiBundle\Entity\Curso();
      $form=$this->createForm(CursoType::class, $curso);

      $form->handleRequest($request);

      if($form->isValid()){
        $status = "Valido!";
        $data = [
          "titulo" => $form->get("titulo")->getData(),
          "descripcion" => $form->get("descripcion")->getData(),
          "precio" => $form->get("precio")->getData()
        ];
      }else{
        $status = null;
        $data = null;
      }

      return $this->render('MiBundle:Default:form.html.twig', [
        'form' => $form->createView(),
        'status' => $status,
        'data' => $data
      ]);
    }

    public function ValidarEmailAction($email)
    {
      $emailConstrainet = new Assert\Email();
      $emailConstrainet->message = "Pasame un buen correo";
      $error = $this->get("validator")->validate(
        $email,
        $emailConstrainet
      );
      if (count($error) == 0) {
        echo "Valido";
      }else{
          echo $error[0]->getMessage();
      }
      die();
    }
}
