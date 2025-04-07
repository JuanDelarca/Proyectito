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
    <form action="" method="post">
            <input type="hidden" name="id" value="">
            <label class="form-label" for="nombre">Nombre Completo:</label>
            <input class="form-control" type="text" name="txtNombre" id=""><br>
        
            <label class="form-label" for="pais">Tipo de gasto</label>
            <select class="form-select" name="cmbPais" id="pais">
                <option value="">Seleciones el tipo de gasto</option>
                <option value="Alimentacion">Alimentacion</option>
                <option value="Transporte">Transporte</option>
                <option value="Salud">Salud</option>
                <option value="Universidad">Universidad</option>
            </select><br>
            <label for="form-label"for="gasto">Valor del Gasto</label>
            <input class="form-control" type="num" name="txtGasto" id=""> <br>
            
            <input class="btn btn-primary" type="submit" value="Enviar" name="submit1">
           
           
        </form>
    
     
    </div>
    
</div>
</body>
</html>