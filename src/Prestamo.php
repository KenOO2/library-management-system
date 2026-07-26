<?php

class Prestamo {
    private $id;
    private $libro_id;
    private $usuario_id;
    private $fecha_prestamo;
    private $fecha_devolucion;
    private $estado;

    public function __construct($libro_id = null, $usuario_id = null) {
        //Inicializar atributos, establecer fecha_prestamo a hoy
        $this->libro_id = $libro_id;
        $this->usuario_id = $usuario_id;
        $this->fecha_prestamo = date('Y-m-d');
        $this->fecha_devolucion = null;
        $this->estado = 'activo';
    }

    // Getters y Setters
    public function getId() {
        //Caundo pedimos el Id del prestamo, lo retornamos
        return $this->id;
    }

    public function getLibroId() {
        //Cuando pedimos el Id del libro, lo retornamos
        return $this->libro_id;
    }

    public function getUsuarioId() {
        // Cuando pedimos el Id del usuario, lo retornamos
        return $this->usuario_id;
    }

    public function getFechaPrestamo() {
        // Cuando pedimos la fecha de prestamo, la retornamos
        return $this->fecha_prestamo;
    }

    public function getFechaDevolucion() {
        // Cuando pedimos la fecha de devolucion, la retornamos
        return $this->fecha_devolucion;
    }

    public function setFechaDevolucion($fecha) {
        // Cuando establecemos la fecha de devolucion, la asignamos al atributo correspondiente
        $this->fecha_devolucion = $fecha;
    }

    public function getEstado() {
        // Cuando pedimos el estado, lo retornamos
        return $this->estado;
    }

    public function setEstado($estado) {
        // Cuando establecemos el estado, lo asignamos al atributo correspondiente
        $this->estado = $estado;
    }
}
