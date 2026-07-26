<?php

class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $isbn;
    private $cantidad;

    public function __construct($titulo = null, $autor = null, $isbn = null, $cantidad = 1) {
 
    // Inicializar atributos de la clase libro
    $this->titulo = $titulo;
    $this->autor = $autor;
    $this->isbn = $isbn;
    $this->cantidad = $cantidad;
    }

    // Getters y Setters
    public function getId() {
        //Cuando pedimos el Id, lo retornamos
        return $this->id;
    }

    public function getTitulo() {
        //Cuando pedimos el Titulo, lo retornamos
        return $this->titulo;
    }
    

    public function setTitulo($titulo) {
        // Asignamos el valor a titulo que mande la instanciacion
        $this->titulo = $titulo;
    }

    public function getAutor() {
      //Cuando pedimos el Autor, lo retornamos
        return $this->autor;
    }

    public function setAutor($autor) {
       // Asignamos el valor a Autor que mande la instanciacion
        $this->autor = $autor;
    }

    public function getIsbn() {
      //Cuando pedimos el Isbn, lo retornamos
        return $this->isbn;
    }

    public function setIsbn($isbn) {
        // Asignamos el valor a Isbn que mande la instanciacion
        $this->isbn = $isbn;
    }

    public function getCantidad() {
        //Cuando pedimos la Cantidad, la retornamos
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        // Asignamos el valor a Cantidad que mande la instanciacion
        $this->cantidad = $cantidad;
    }

}
