<?php

    require 'Conexion.php';


    $error="";
    $hay_post="";
    $codigoGasto=null;
    $nombre="";
    $tipoGasto="";
    $valorGasto="";

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

        if(!$error){
            $stm_insertarRegistro = $conexion->prepare("INSERT INTO gastos(nombre, tipoGasto, valorGasto) VALUES(:nombre, :tipoGasto, :valorGasto)");
            $stm_insertarRegistro->execute([':nombre'=>$nombre, ':tipoGasto'=>$tipoGasto, ':valorGasto'=>$valorGasto]);
            header("Location: Proyectito.php?mensaje=registroGuardado");
            exit();
        }
    }


    $stm_listar = $conexion->prepare("SELECT * FROM gastos");
    $stm_listar->execute();
    $Resultado = $stm_listar->fetchAll(PDO::FETCH_ASSOC);




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

    <h1 class="text-center">Proyectito</h1>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="id" value="<?php echo isset($codigoGasto)? $codigoGasto :""?>">
            <label class="form-label" for="nombre">Nombre Persona:</label>
            <input class="form-control" type="text" name="txtNombre" id="nombre" value="<?php echo isset($nombre)? $nombre : ""?>"><br>
        
            <label class="form-label" for="tipoGasto">Tipo de gasto</label>
            <select class="form-select" name="cmbtipoGasto" id="tipoGasto">
                <option value="">Seleciones el tipo de gasto</option>
                <option value="Alimentación" <?php echo($tipoGasto=='Alimentación')? 'selected' : ''?>>Alimentación</option>
                <option value="Transporte" <?php echo($tipoGasto=='Transporte')? 'selected' : ''?>>Transporte</option>
                <option value="Salud" <?php echo($tipoGasto=='Salud')? 'selected' : ''?>>Salud</option>
                <option value="Cine" <?php echo($tipoGasto=='Cine')? 'selected' : ''?>>Cine</option>
                <option value="Provisión"<?php echo($tipoGasto=='Provisión')? 'selected' : ''?>>Provisión</option>
                <option value="Universidad"<?php echo($tipoGasto=='Universidad')? 'selected' : ''?>>Universidad</option>
                <option value="Educación" <?php echo($tipoGasto=='Educación')? 'selected' : ''?>>Educación</option>
                <option value="Entretenimiento" <?php echo($tipoGasto=='Entrenimiento')? 'selected' : ''?>>Entretenimiento</option>
            </select><br>
            <label for="form-label"for="gasto">Valor del Gasto</label>
            <input class="form-control" type="number" name="txtGasto" id=""> <br>
            <input class="btn btn-primary" type="submit" value="Enviar" name="submit1"> <br>
        </form>
        
           

        <table class="table table-bordered table-hover"><br>
            <thead>
                <th>Nombre</th>
                <th>Tipo de gasto</th>
                <th>Valor</th>
                <th colspan="2">Aciones</th>
            </thead>
            <tbody>
                <?php foreach($Resultado  as $registro):?>
                    <tr>
                    <td><?php echo $registro['nombre']?></td>
                    <td><?php echo $registro['tipoGasto']?></td>
                    <td><?php echo $registro['valorGasto']?></td>

                    <?php endforeach; ?>


                    </tr>

            </tbody>

        </table>



    
     
    </div>
    
</div>
</body>
</html>