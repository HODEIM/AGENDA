<?php

class agenda1
{
    private $agenda;
    private $titulo;

    function __construct()
    {
        if (!isset($_POST["cadena"])) {
            $this->agenda = array();
        } else {
            $this->agenda = json_decode($_POST["cadena"], true);
        }
    }
    // Aqui miro si el campo de nombre tiene valor o no
    function nombreVacio()
    {
        if (isset($_POST["nombre"])) {
            if ($_POST["nombre"] != "") {
                return false;
            } else {
                return true;
            }
        }
    }
    // mira si el nombre que acabamos de insertar ya estaba en la agenda
    function comprobarNombre()
    {
        $nombre = array_keys($this->agenda);
        foreach ($nombre as $nombreAux) {
            if (strtolower($nombreAux) == strtolower($_POST["nombre"])) {
                return true;
            }
        }
        return false;
    }

    // si no hay ninguno con el nombre, lo crea
    function anadirNuevo()
    {
        if (isset($_POST["nombre"]) && isset($_POST["correo"])) {
            $this->agenda[strtolower(htmlentities($_POST["nombre"]))] = htmlentities($_POST["correo"]) . ",";
        }
    }
    // cuando ya hay un nombre, le agrega el nuevo
    function actualizarNuevo()
    {
        if (isset($_POST["nombre"]) && isset($_POST["correo"])) {
            $this->agenda[strtolower(htmlentities($_POST["nombre"]))] .= htmlentities($_POST["correo"]) . ",";
        }
    }


    // Comprobar si tiene una @ lo compruebo directamente en el layout, aqui miro si tiene un punto
    function comprobarCorreo()
    {
        if ($_POST["correo"] != "") {
            $correo = $_POST["correo"];
            if (strpos($correo, '.') !== false) {
                return true;
            } else {
                return false;
            }
        }
    }



    function correoVacio()
    {
        if ($_POST["correo"] != "") {
            return false;
        } else {
            return true;
        }
    }

    // Este metodo se supone que debería de vaciarme el array del nombre que tiene, 
    // PERO POR ALGUN MOTIVO, EL UNSET NO ME FUNCIONA Y NO LO ELIMINA

    function eliminarCorreos()
    {
        if (array_key_exists(strtolower($_POST["nombre"]), $this->agenda)) {
            unset($this->agenda[strtolower($_POST["nombre"])]);
            echo "<p style=color:red;>Eliminado correctamente</p>";
        } else {
            echo "<p style=color:red;>No hay registros para " . $_POST["nombre"] . "</p>";
        }
    }

    function getAgenda()
    {
        return $this->agenda;
    }

    function crearCadena()
    {
        $cadena = json_encode($this->agenda);
        echo $cadena;
    }
    function crearTitulo()
    {
        echo $this->titulo;
    }
    function getTitulo()
    {
        return $this->titulo;
    }
    function setTitulo($tit)
    {
        $this->titulo = $tit;
    }
}


?>
<!DOCTYPE html>

<html>

<head>
    <title>Mi agenda</title>
</head>

<body>
    <?php $obj = new agenda1(); ?>
    <?php
    
    $dequien = "";
    if (isset($_POST["dequien"])) {
        $dequien =  htmlentities($_POST["dequien"]);
        $obj->setTitulo($dequien);
        echo "<h1>Agenda de: " . $dequien . "</h1>";
        $_POST["title"] = $dequien;
    }
    if (isset($_POST["titulo"])) {
        $dequien = $obj->getTitulo();
        $obj->setTitulo($dequien);
        $_POST["title"] = $dequien;
        echo "<h1>Agenda de: " . $dequien . "</h1>";
    }

    

    ?>

    <pre>
        <?php
        $obj = new agenda1();
        if (isset($_POST["cadena"])) {
            if (!$obj->nombreVacio()) {
                if ($obj->comprobarCorreo()) {
                    if ($obj->comprobarNombre()) {
                        $obj->actualizarNuevo();
                    } else {
                        $obj->anadirNuevo();
                    }
                } else {
                    if ($obj->correoVacio()) {
                        $obj->eliminarCorreos();
                    } else {
                        echo "<p style=color:red;>El formato del correo no es el correcto</p>";
                    }
                }
            } else {
                echo "<p style=color:red;>El nombre no puede estar vacio</p>";
            }
        }

        ?>
    </pre>
    <form method="POST">
        <table>
            <tr>
                <td>Intruzca el nombre: </td>
                <td><input type="text" name="nombre" value=<?php if (isset($_POST["nombre"])) {
                                                                echo htmlentities($_POST["nombre"]);
                                                            } ?>></td>
            </tr>
            <tr>
                <td>Intruzca el correo: </td>
                <td><input type="text" name="correo" value=<?php if (isset($_POST["correo"])) {
                                                                echo htmlentities($_POST["correo"]);
                                                            } ?>></td>
            </tr>
            <tr>
                <td><input type="submit" value="Guardar datos" /></td>
                <td><input type="hidden" name="cadena" value=<?php $obj->crearCadena() ?>></td>
                <td><input type="hidden" name="titulo" value=<?php $obj->crearTitulo() ?>></td>
            </tr>
        </table>
    </form>
    <pre>
        <?php
        if (!empty($obj->getAgenda())) {
            echo "<table style=border:solid;>";
            foreach ($obj->getAgenda() as $clave => $valores) {
                echo "<tr>";
                echo "<th style=color:red;>" . strtoupper($clave) . ": </th>";
                $cadaNombre = explode(",", $valores);
                for ($i = 0; $i < count($cadaNombre); $i++) {
                    if ($cadaNombre[$i] != "") {
                        echo "<td style=border:solid;>" . $cadaNombre[$i] . "</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        ?>
    </pre>


</body>

</html>