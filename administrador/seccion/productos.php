<!-- Productos ofrecidos -->
<?php include("../template/cabecera.php"); ?>

<?php
    $txtID=(isset($_POST['txtID'])) ? $_POST['txtID']: "";
    $txtNombre=(isset($_POST['txtNombre'])) ? $_POST['txtNombre']: "";
    $txtImagen=(isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name']: "";
    $accion=(isset($_POST['accion'])) ? $_POST['accion']: "";

    include("../config/bd.php");
    
    switch($accion){
        case 'Agregar':
            // crud para la creacion de nuevo producto en el sistema
            $crud = $conexion-> prepare("INSERT INTO productos (nombre, imagen) VALUES (:nombre, :imagen);");
            $crud-> bindParam(':nombre', $txtNombre);
            $crud-> bindParam(':imagen', $txtImagen);

            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen!="") ? ($fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]) : ("imagen.jpg");

            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!= ""){
                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
            }

            
            $crud->execute();
            break;

        case 'Modificar':
            // Para editar nombre
            $crud = $conexion-> prepare("UPDATE productos SET nombre = :nombre WHERE id= :id");
            $crud-> bindParam(':nombre', $txtNombre);
            $crud-> bindParam(':id', $txtID);
            $crud->execute();

            // Para editar imagen
            if($txtImagen != ""){

                $fecha = new DateTime();
                $nombreArchivo = ($txtImagen!="") ? $fecha->getTimestamp()."_". $_FILES["txtImagen"]["name"] : "imagen.jpg";

                $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

                $crud = $conexion-> prepare("SELECT imagen FROM productos WHERE id= :id");
                $crud-> bindParam(':id', $txtID);
                $crud->execute();
                $datos = $crud-> fetch(PDO::FETCH_LAZY);

                if( isset($datos["imagen"]) && ($datos["imagen"]!="imagen.jpg") && (file_exists("../../img/".$datos["imagen"]))){
                    echo "go";
                    unlink("../../img/".$datos["imagen"]);
                }

                $crud = $conexion-> prepare("UPDATE productos SET imagen =  :imagen WHERE id= :id");
                $crud-> bindParam(':imagen', $nombreArchivo);
                $crud-> bindParam(':id', $txtID);
                $crud->execute();
            }
            break;

        case 'Cancelar':
            echo 'Canceló';
            break;
        case 'Seleccionar':
            $crud = $conexion-> prepare("SELECT * FROM productos WHERE id= :id");
            $crud-> bindParam(':id', $txtID);
            $crud->execute();
            $datos = $crud-> fetch(PDO::FETCH_LAZY);
            $selecNombre = $datos['nombre'];
            $selecImagen = $datos['imagen'];
            break;
        case 'Borrar':
            $crud = $conexion-> prepare("SELECT imagen FROM productos WHERE id= :id");
            $crud-> bindParam(':id', $txtID);
            $crud->execute();
            $datos = $crud-> fetch(PDO::FETCH_LAZY);

            if( isset($datos['imagen']) && ($datos['imagen']!="imagen.jpg")){
                if(file_exists("../../img/".$datos['imagen'])){
                    unlink("../../img/".$datos['imagen']);
                }
            }
            $crud = $conexion-> prepare("DELETE FROM productos WHERE id = :id ");
            $crud-> bindParam(':id', $txtID);
            $crud->execute();
            break;
        default:
        break;
    }

    $crud = $conexion-> prepare("SELECT id, nombre, imagen FROM productos");
    $crud->execute();
    $listaProductos = $crud-> fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">
    
    <form method="POST" enctype="multipart/form-data" >

        <!-- Formulario para la consulta, ingreso, edición y eliminación de los productos -->
        <div class="card">
            <div class="card-header">
                Datos del Producto
            </div>

            <div class="card-body">
                <div class = "form-group">
                    <label for="exampleInputEmail1">ID:</label>
                    <input type="text" required readonly class="form-control" id="txtID" name="txtID" aria-describedby="emailHelp" placeholder="Ingrese el ID del producto" value="<?php 
                        switch ($accion) {
                            case 'Seleccionar':
                                echo $txtID;
                                break;
                        }
                    ?>" >
                </div>

                <div class = "form-group">
                    <label for="exampleInputEmail1">Nombre:</label>
                    <input type="text" required class="form-control" id="txtNombre" name="txtNombre" placeholder="Ingrese el nombre del producto" value="<?php 
                        switch ($accion) {
                            case 'Seleccionar':
                                echo $selecNombre;
                                break;
                        }
                    ?>">
                </div>

                <div class = "form-group">
                    <label for="exampleInputEmail1">Imagen:</label>
                    <?php
                        switch ($accion) {
                            case 'Seleccionar':
                                echo $selecImagen;
                                break;
                        }
                    ?>
                    <input type="file" required class="form-control" id="txtImagen" name="txtImagen" placeholder="Ingrese el nombre del producto" style="height: 45px; margin: 10px auto;">
                </div>

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" class="btn btn-primary" value="Agregar" style="margin-right: 9px;" name="accion" >Agregar</button>
                    <button type="submit" class="btn btn-warning" value="Modificar" style="margin-right: 9px;" name="accion">Modificar</button>
                    <button type="submit" value="Cancelar" class="btn btn-info"  name="accion">Cancelar</button>
                </div>
            </div>
        </div> 
    </form>  
</div>

<div style="margin-top: 10px;" class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($listaProductos as $Producto ) {
            ?>
            <tr>
                <td><?php echo $Producto['id']; ?></td>
                <td><?php echo $Producto['nombre']; ?></td>
                <td>
                    <img src="../../img/<?php echo $Producto['imagen']; ?>" alt="Imágen del producto" width="50">
                    </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $Producto['id']; ?>">
                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" style="margin: 5px;">
                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger" style="margin: 5px;">
                    </form>

                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
</div>

<?php include("../template/pieDePag.php"); ?>