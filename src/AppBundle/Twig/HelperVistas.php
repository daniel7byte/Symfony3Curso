<?php
namespace AppBundle\Twig;

/**
 *
 */
class HelperVistas extends \Twig_Extension{
  public function getFunctions(){
    return [
      'generateTable' => new \Twig_Function_Method($this, 'generateTable')
    ];
  }

  public function generateTable($rows, $col){
    $table = '<table border=1>';
    for ($i=0; $i < $col ; $i++) {
      $table .= '<tr>';
      for ($f=0; $f < $rows; $f++) {
        $table .= '<td>COLUMNA</td>';
      }
      $table .= '</tr>';
    }
    $table .= '</table>';
    return $table;
  }

  public function getName(){
    return "app-bundle";
  }
}
