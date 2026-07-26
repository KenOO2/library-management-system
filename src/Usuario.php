<?php

class Usuario {
    private $id;
    private $nombre;
    private $email;
    private $telefono;

    // Constructor de la clase Usuario
    public function __construct($nombre = null, $email = null, $telefono = null) {

        // Inicializar atributos en null si no se proporcionan valores
        $this->nombre = $nombre;
        $this->email = $email;
        $this->telefono = $telefono;
    }

    // Getters y Setters
    public function getId() {
        //Cuando pedimos el id, lo retornamos
        return $this->id;
    }

    public function getNombre() {
        //Cuando pedimos el nombre, lo retornamos
        return $this->nombre;
    }

    public function setNombre($nombre) {
        //Cuando establecemos el nombre, lo asignamos al atributo correspondiente
        $this->nombre = $nombre;
    }

    public function getEmail() {
        // Cuando pedimos el email, lo retornamos
        return $this->email;
    }

    public function setEmail($email) {
        // Cuando establecemos el email, lo asignamos al atributo correspondiente
        $this->email = $email;
    }

    public function getTelefono() {
        // Cuando pedimos el teléfono, lo retornamos
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        // Cuando establecemos el teléfono, lo asignamos al atributo correspondiente
        $this->telefono = $telefono;
    }
}
