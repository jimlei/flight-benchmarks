<?php

use Doctrine\ORM\Query;

class BenchmarkDoctrineController {
  
  public static function index() {
    Flight::view()->display('index.twig');
  }
  
  public static function json() {
    $return = new \stdClass();
    $return->message = 'Hello World';
    Flight::json($return);
  }
  
  public static function db() {
    Flight::json(self::getWorldRow());
  }
  
  public static function queries() {
    $iterations = Flight::request()->query->queries > 1 && Flight::request()->query->queries <= 500
      ? (int) Flight::request()->query->queries
      : 1;
    
    $return = [];
    for ($i = 0; $i < $iterations; $i++) {
      $return[] = self::getWorldRow();
    }
    
    Flight::json($return);
  }
    
  public static function fortunes() {
    $fortunes = Flight::db()->getRepository('Fortune')->findAll();
    
    $fortune = new Fortune;
    $fortune->setMessage('Additional fortune added at request time.');
    $fortunes[] = $fortune;
    
    usort($fortunes, function($left, $right) {
        return strcmp($left->getMessage(), $right->getMessage());
    });
    
    Flight::view()->display('fortunes.twig', [
      'fortunes' => $fortunes
    ]);
  }
  
  public static function updates() {
    $iterations = Flight::request()->query->queries > 1 && Flight::request()->query->queries <= 500
      ? (int) Flight::request()->query->queries
      : 1;
    
    $return = [];
    for ($i = 0; $i < $iterations; $i++) {
      $row = self::getWorldRow(false);
      
      $row->setRandomNumber(rand(1, 10000));
      Flight::db()->persist($row);
      Flight::db()->flush();
      
      $return[] = [
        'id' => $row->getId(),
        'randomNumber' => $row->getRandomNumber()
      ];
    }
    
    Flight::json($return);
  }
  
  public static function plaintext() {
    header('Content-type: text/plain');
    echo 'Hello, World!';
  }
  
  private static function getWorldRow($hydrate = true) {
    $qb = Flight::db()->createQueryBuilder()
      ->from('World', 'w')
      ->select('w')
      ->where('w.id = :id')
      ->setParameter('id', rand(1, 10000));
    
    return $hydrate
      ? $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY)
      : $qb->getQuery()->getSingleResult();
  }
  
}