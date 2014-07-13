<?php

use Doctrine\ORM\Query;

class BenchmarkRedbeanController {
  
  public static function index() {
    Flight::view()->display('index.twig');
  }
  
  public static function json() {
    $return = new \stdClass();
    $return->message = 'Hello World';
    Flight::json($return);
  }
  
  public static function db() {
    $return = R::findOne('world', 'id = ?', [rand(1, 10000)]);
    Flight::json(R::beansToArray([$return]));
  }
  
  public static function queries() {
    $iterations = Flight::request()->query->queries > 1 && Flight::request()->query->queries <= 500
      ? (int) Flight::request()->query->queries
      : 1;
    
    $return = [];
    for ($i = 0; $i < $iterations; $i++) {
      $return[] = R::findOne('world', 'id = ?', [rand(1, 10000)]);
    }
    
    Flight::json(R::beansToArray($return));
  }
  
  public static function fortunes() {
    $fortunes = R::findAll('fortune');
    
    $fortune = R::dispense('fortune');
    $fortune->message = 'Additional fortune added at request time.';
    $fortunes[] = $fortune;
    
    usort($fortunes, function($left, $right) {
        return strcmp($left->message, $right->message);
    });
    
    Flight::view()->display('@default/fortunes.twig', [
      'fortunes' => $fortunes
    ]);
  }
  
  public static function updates() {
    $iterations = Flight::request()->query->queries > 1 && Flight::request()->query->queries <= 500
      ? (int) Flight::request()->query->queries
      : 1;
    
    $return = [];
    for ($i = 0; $i < $iterations; $i++) {
      $row = R::findOne('world', 'id = ?', [rand(1, 10000)]);
      $row->randomNumber(rand(1, 10000));
      R::store($row);
      $return[] = $row;
    }
    
    Flight::json(R::beansToArray($return));
  }
  
  public static function plaintext() {
    header('Content-type: text/plain');
    echo 'Hello, World!';
  }
  
}