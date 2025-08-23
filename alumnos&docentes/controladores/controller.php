
<?php
require_once '../models/Docente.php';

class DocenteController {
    public function index() {
        $docente = new Docente();
        $docentes = $docente->getAll();
        
        header('Content-Type: application/json');
        echo json_encode($docentes);
    }

    public function create($data) {
        $docente = new Docente();
        $docente->nombre = $data['nombre'];
        $docente->materia = $data['materia'];
        
        $result = $docente->create();
        
        header('Content-Type: application/json');
        echo json_encode(["success" => $result]);
    }

    public function update($id, $data) {
        $docente = new Docente();
        $docente->id = $id;
        $docente->nombre = $data['nombre'];
        $docente->materia = $data['materia'];
        
        $result = $docente->update();
        
        header('Content-Type: application/json');
        echo json_encode(["success" => $result]);
    }

    public function delete($id) {
        $docente = new Docente();
        $docente->id = $id;
        
        $result = $docente->delete();
        
        header('Content-Type: application/json');
        echo json_encode(["success" => $result]);
    }
}
?>
