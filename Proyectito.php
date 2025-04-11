<?php

    require 'Conexion.php';


    $error="";
    $hay_post="";
    $codigoGasto=null;
    $nombre="";
    $tipoGasto="";
    $valorGasto="";
    $Resultado = []; 

    if(isset($_REQUEST['submit1'])){
        $hay_post =true;
        $nombre = isset($_REQUEST['txtNombre'])? $_REQUEST['txtNombre'] : "";
        $tipoGasto = isset($_REQUEST['cmbtipoGasto'])? $_REQUEST['cmbtipoGasto'] : "";
        $valorGasto = isset($_REQUEST['txtGasto'])? $_REQUEST['txtGasto'] :"";

        if(!empty($nombre)){
            $nombre = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ]/u","",$nombre);
        }
        else{
            $error .= "El nombre no puede esta vácio<br>";
        }

        if($tipoGasto == ""){
            $error .= "Selecione un gasto<br>"; 
        }
        if($valorGasto == ""){
            $error .="Ponga una cantidad<br>";
        }
        if($valorGasto < 0){
            $error .="Ponga un numero positivo<br>";
        }

        
        

        if(!$error){
            $stm_insertarRegistro = $conexion->prepare("INSERT INTO gastos(nombre, tipoGasto, valorGasto) VALUES(:nombre, :tipoGasto, :valorGasto)");
            $stm_insertarRegistro->execute([':nombre'=>$nombre, ':tipoGasto'=>$tipoGasto, ':valorGasto'=>$valorGasto]);
            header("Location: Proyectito.php?mensaje=registroGuardado");
            exit();
        }
    }



    if(isset($_REQUEST['submit2'])){
        $hay_post = true;
        $nombre = isset($_REQUEST['txtNombre'])? $_REQUEST['txtNombre'] : "";
        $tipoGasto = isset($_REQUEST['cmbtipoGasto'])? $_REQUEST['cmbtipoGasto'] : "";
        $valorGasto = isset($_REQUEST['txtGasto'])? $_REQUEST['txtGasto'] :"";
        $codigoGasto = isset($_REQUEST['id']) ? $_REQUEST['id'] : null; // ← ESTA LÍNEA FALTABA
    
        if(!empty($nombre)){
            $nombre = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ]/u","", $nombre);
        } else {
            $error .= "El nombre no puede estar vacío<br>";
        }
    
        if($tipoGasto == ""){
            $error .= "Seleccione un gasto<br>"; 
        }
        if($valorGasto == ""){
            $error .= "Ponga una cantidad<br>";
        }
        if($valorGasto < 0){
            $error .="Ponga un numero positivo<br>";
        }
    
        if(!$error){
            $stm_modificar = $conexion->prepare("UPDATE gastos SET nombre = :nombre, tipoGasto = :tipoGasto, valorGasto = :valorGasto WHERE codigoGasto = :id");
            $stm_modificar->execute([
                ':nombre' => $nombre,
                ':tipoGasto' => $tipoGasto,
                ':valorGasto' => $valorGasto,
                ':id' => $codigoGasto 
            ]);
            header("Location: Proyectito.php?mensaje=registroModificado");
            exit();
        }
    }
    


    
    if(isset($_REQUEST['id']) && isset($_REQUEST['op'])){
        $id = $_REQUEST['id'];
        $op = $_REQUEST['op'];
    
        if($op == 'm'){
            $stm_selecionarRegistro = $conexion->prepare("SELECT * FROM gastos WHERE codigoGasto=:codigoGasto");
            $stm_selecionarRegistro->execute([':codigoGasto' => $id]);
            $fila = $stm_selecionarRegistro->fetch();
            if ($fila) {
                $codigoGasto = $fila['codigoGasto'];
                $nombre = $fila['nombre'];
                $tipoGasto = $fila['tipoGasto'];
                $valorGasto = $fila['valorGasto'];
            }
        } else if($op == 'e'){
            $stm_eliminar = $conexion->prepare("DELETE FROM gastos WHERE codigoGasto=:codigoGasto");
            $stm_eliminar->execute([':codigoGasto' => $id]);
            header("Location: Proyectito.php?mensaje=registroeliminado");
            exit(); 
        }
    }



    

    if (isset($_REQUEST['buscar']) && !empty(trim($_REQUEST['buscar']))) {
        $buscar = '%' . trim($_REQUEST['buscar']) . '%';
        $stm_listar = $conexion->prepare("SELECT * FROM gastos 
            WHERE nombre LIKE :busqueda 
            OR tipoGasto LIKE :busqueda 
            OR valorGasto LIKE :busqueda");
        $stm_listar->execute([':busqueda' => $buscar]);
    } else {
        $stm_listar = $conexion->prepare("SELECT * FROM gastos");
        $stm_listar->execute();
    }
    $Resultado = $stm_listar->fetchAll(PDO::FETCH_ASSOC);

    $totalGastos = 0;
    foreach ($Resultado as $registro) {
    $totalGastos += $registro['valorGasto'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <h1 class="text-center fw-bolder">Proyectito</h1>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="id" value="<?php echo isset($codigoGasto)? $codigoGasto :""?>">
            <label class="form-label fw-bolder" for="nombre">Nombre Persona:</label> <br>
            <input class="form-control border-primary-subtle" type="text" name="txtNombre" id="nombre" value="<?php echo isset($nombre)? $nombre : ""?>"><br>
        
            <label class="form-label fw-bolder" for="tipoGasto">Tipo de gasto:</label>
            <select class="form-select border-primary-subtle" name="cmbtipoGasto" id="tipoGasto">
            <option value="">Seleccione el tipo de gasto</option>
            <option value="Alimentación" <?php echo ($tipoGasto == 'Alimentación') ? 'selected' : ''; ?>>Alimentación</option>
            <option value="Transporte" <?php echo ($tipoGasto == 'Transporte') ? 'selected' : ''; ?>>Transporte</option>
            <option value="Salud" <?php echo ($tipoGasto == 'Salud') ? 'selected' : ''; ?>>Salud</option>
            <option value="Cine" <?php echo ($tipoGasto == 'Cine') ? 'selected' : ''; ?>>Cine</option>
            <option value="Provisión" <?php echo ($tipoGasto == 'Provisión') ? 'selected' : ''; ?>>Provisión</option>
            <option value="Universidad" <?php echo ($tipoGasto == 'Universidad') ? 'selected' : ''; ?>>Universidad</option>
            <option value="Educación" <?php echo ($tipoGasto == 'Educación') ? 'selected' : ''; ?>>Educación</option>
            <option value="Entretenimiento" <?php echo ($tipoGasto == 'Entretenimiento') ? 'selected' : ''; ?>>Entretenimiento</option>
            </select><br>

            <label class= "fw-bolder"for="form-label "for="valorGasto">Valor del Gasto:</label>
            <input class="form-control border-primary-subtle" type="number" name="txtGasto" id="gasto" value="<?php echo isset($valorGasto)? $valorGasto : '' ?>"> <br>
             <br>
            

            <?php
                if($codigoGasto){
                    echo '<input class="btn btn-dark" type="submit" value="Modificar" name="submit2">';
                }else {

                    echo '<input class="btn btn-primary" type="submit" value="Enviar" name="submit1">';
            
                }
                ?>
            <a class="btn btn-secondary" href="Proyectito.php">Cancelar</a>
        </form>
        <br>

        <?php if($error):  ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo "<p>$error</p>"; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
        <?php endif; ?>
        <br>


        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="mb-3">
        <label class="fw-bolder"for="buscar">Buscar:</label>
        <input type="text" id="buscar" name="buscar" placeholder="Escribe tu búsqueda aquí" class="form-control d-inline-block w-auto" value="<?php echo isset($_REQUEST['buscar']) ? htmlspecialchars($_REQUEST['buscar']) : ''; ?>">
        <button type="submit" class="btn btn-success">Buscar</button>
        </form>

        <br>


           

        <?php
            if(isset($_REQUEST['mensaje'])){
                $mensaje = $_REQUEST['mensaje'];
        ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <?php
                    if($mensaje=='registroGuardado'){
                        echo "<p>Registro guardado.</p>";
                    }
                    elseif($mensaje == 'registroModificado'){
                        echo "<p>Registro modificado.</p>";
                    }
                    elseif($mensaje=='registroEliminado'){
                        echo "<p>Registro eliminado.</p>";
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
            }
        ?>

        <table class="table table-bordered table-hover border "><br>
            <thead class="border border-black table-success ">
                <th>Nombre</th>
                <th>Tipo de gasto</th>
                <th>Valor</th>
                <th colspan="2">Aciones</th>
            </thead>
            <tbody class="table-group-divider border border-black">
                <?php foreach($Resultado  as $registro):?>
                    <tr>
                    <td><?php echo $registro['nombre']?></td>
                    <td><?php echo $registro['tipoGasto']?></td>
                    <td><?php echo $registro['valorGasto']?></td>


                    <td><a class="btn btn-primary" href="Proyectito.php?id=<?php echo $registro['codigoGasto'] ?>&op=m">Modificar</a></td>
                    <td><a class="btn btn-danger" href="Proyectito.php?id=<?php echo $registro['codigoGasto'] ?>&op=e" onclick="return confirm('Desea eliminar el registro');">Eliminar</a></td>
                    <?php endforeach; ?>


                    </tr>

            </tbody>
            <tr>
            <td colspan="2" class="text-end"><strong>Total acumulado:</strong></td>
            <td><strong><?php echo number_format($totalGastos, 2); ?></strong></td>
            <td colspan="2"></td>
            </tr>


        </table>



    
     
    </div>
    
</div>
</body>
</html>