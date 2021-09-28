<html>
    <head>
        <title>CREADOR DE GRUPOS</title>
        <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script>
            function doNothing(){
                // :)
            }

            function insertRandName(target_id, name_array) {
                var rand_index = Math.floor(Math.random() * name_array.length);
                document.getElementById(target_id).innerHTML = name_array[rand_index];
            }

            function insertName(target_id, alumno){
                document.getElementById(target_id).innerHTML = alumno;
            }

            function slot(array_grupos, array_alumnos){
                var duracion =  0;
                var duracion_total = 4000
                var ciclos = 10;
                var duracion_inc = duracion_total / array_alumnos.length / ciclos;

                var contAl = 1;
                array_grupos.forEach((grupo, Ngrupo) => {
                    grupo.forEach((alum, Nalumno) => {
                        setTimeout(() => insertName(Nalumno + '' + Ngrupo, alum), duracion_inc*(ciclos+1)*contAl );
                        for (let i = 0; i < ciclos; i++) {
                            setTimeout(() => insertRandName(Nalumno + '' + Ngrupo, array_alumnos), duracion);
                            duracion = duracion + duracion_inc;   
                        }
                        contAl++;
                    });
                });
            }

        </script>
    </head>
    <body>
        <div>
            <h1>CREADOR DE GRUPOS</h1>
            <form id="form-fields" method=get>
                <input name=total type=number placeholder="num personas totales">
                <input name=grupo type=number placeholder="num personas por grupo">
                <input id="submit" type=submit value=añadir id=listo>
            </form>

            <?php
                session_start();
                if (isset($_GET['total'])){
                    $_SESSION['total']=$_GET['total'];
                    $_SESSION['grupo']=$_GET['grupo'];
                    
                    echo "<form method=get>";
                    for ($x=0; $x<$_SESSION['total']; $x++){
                        echo "<input name=alum" . $x . " type=text placeholder=alumno/alumna " . $x . "><br>";
                    }
                    echo "<br><input type=submit value=agrupa>
                        </form>";
                }

                if (isset($_GET['alum0'])){
                    $total = $_SESSION['total'];
                    $num_grupo = $_SESSION['grupo'];

                    $alumnos = array();
                    for ($x = 0; $x < $total; $x++){
                        array_push($alumnos, $_GET["alum" . $x]);
                    }
                    shuffle($alumnos);

                    $asignados= 0;
                    
                    $grupos = array();
        
                    while ($asignados<$total){
                        $grupo = array();

                        if ($total-$asignados==$num_grupo+1){ //para que no quede uno suelto
                            $num_grupo=round($num_grupo/2);
                        }

                        if ($total-$asignados<$num_grupo){ //para que no se salga de la lista
                            for ($y = 0; $y <= $total-$asignados; $y++){
                                array_push($grupo, $alumnos[$asignados]);
                                $asignados++;
                            }
                            
                        } else {
                            for ($y = 0; $y < $num_grupo; $y++){
                                array_push($grupo, $alumnos[$asignados]);
                                $asignados++;
                            }
                        }
                        array_push($grupos, $grupo);
                    }

                    
                    $contY=0;
                    echo "<table>";
                    foreach ($grupos as $gru) {
                        $contX=0;
                        echo "<tr><td><b>GRUPO " . ($contY+1) . "</b></td>";
                        foreach ($gru as $alu) {
                            echo "<td id=" . $contX . $contY . "></td>";
                            $contX++;
                        }
                        echo "</tr>";
                        $contY++;
                    }

                    $jsvar1 = json_encode($grupos);
                    $jsvar2 = json_encode($alumnos);

                    echo "
                        <script>
                            slot(($jsvar1),($jsvar2));
                        </script>";
                }
            ?>
        </div>
    </body>
    <footer>
    </footer>
</html>