<?php
$ruta = explode("/", $_GET['views']);
//if (!isset($ruta[1]) || $ruta[1]=="") { //si no existe la informacion
    //header ("location: " .BASE_URL. "bienes");
//}

    $curl = curl_init(); //inicia la sesión cURL
    curl_setopt_array($curl, array(
        CURLOPT_URL => BASE_URL_SERVER."src/control/Institucion.php?tipo=listar&sesion=".$_SESSION['sesion_id']."&token=".$_SESSION['sesion_token'],
        CURLOPT_RETURNTRANSFER => true, //devuelve el resultado como una cadena del tipo curl_exec
        CURLOPT_FOLLOWLOCATION => true, //sigue el encabezado que le envíe el servidor
        CURLOPT_ENCODING => "", // permite decodificar la respuesta y puede ser"identity", "deflate", y "gzip", si está vacío recibe todos los disponibles.
        CURLOPT_MAXREDIRS => 10, // Si usamos CURLOPT_FOLLOWLOCATION le dice el máximo de encabezados a seguir
        CURLOPT_TIMEOUT => 30, // Tiempo máximo para ejecutar
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // usa la versión declarada
        CURLOPT_CUSTOMREQUEST => "GET", // el tipo de petición, puede ser PUT, POST, GET o Delete dependiendo del servicio
        CURLOPT_HTTPHEADER => array(
            "x-rapidapi-host: ".BASE_URL_SERVER,
            "x-rapidapi-key: XXXX"
        ), 
    )); 
    $response = curl_exec($curl); 
    $err = curl_error($curl); 
    curl_close($curl); 
    if ($err) {
        echo "cURL Error #:" . $err; 
    } else {
    
       $respuesta = json_decode($response);

       $instituciones = $respuesta->contenido;

    // datos para la fechas
        $new_Date = new DateTime();
        $dia = $new_Date->format('d');
        $año = $new_Date->format('Y');
        $mesNumero = (int)$new_Date->format('n'); 
        $meses = [1 => 'Enero',2 => 'Febrero',3 => 'Marzo', 4 => 'Abril',5 => 'Mayo', 6 => 'Junio', 7 => 'Julio',8 => 'Agosto',9 => 'Septiembre',10 => 'Octubre',11 => 'Noviembre', 12 => 'Diciembre'];

       $contenido_pdf = '';

       $contenido_pdf .= '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Papeleta de Rotación de instituciones</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    h2 {
      text-align: center;
      text-transform: uppercase;
    }
    .info {
      margin-bottom: 20px;
      line-height: 1.8;
    }
    .info b {
      display: inline-block;
      width: 80px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      font-size:9px;
    }
    th, td {
      border: 1px solid black;
      text-align: center;
      padding: 6px;
    }
    .fecha {
      margin-top: 30px;
      text-align: right;
    }

    .firma-section tr td{
       border: none;
      }

  </style>
</head>
<body>

  <h2>REPORTE DE INSTITUCIONES</h2>

  <table>
    <thead>
      <tr>
        <th>ITEM</th>
        <th>BENEFICIARIO</th>
        <th>CODIGO MODULAR</th>
        <th>RUC</th>
        <th>NOMBRE</th>
      </tr>
    </thead>
    <tbody>';    
         $contador = 1;
        foreach ($instituciones as $institucion) {
             $contenido_pdf .= '<tr>';
             $contenido_pdf .=  "<td>".  $contador . "</td>";
             $contenido_pdf .=  "<td>".  $institucion->beneficiario . "</td>";
             $contenido_pdf .= "<td>" .  $institucion->cod_modular . "</td>";
             $contenido_pdf .=  "<td>".  $institucion->ruc . "</td>";
             $contenido_pdf .=  "<td>".  $institucion->nombre. "</td>";
             $contenido_pdf .=  '</tr>';
             $contador ++;
        }
 $contenido_pdf .='  </tbody>
  </table> 

  <div class="fecha">
    Ayacucho, '. $dia . " de " . $meses[$mesNumero] . " del " . $año.'
  </div>
<table  class="firma-section">
  <tr>
  <td>
    <div>
      ------------------------------<br>
      ENTREGUÉ CONFORME
    </div>
    </td>
    <td>
    <div>
      ------------------------------<br>
      RECIBÍ CONFORME
    </div>
    </td>
   </tr>
  </table>

</body>
</html>
';


   
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');

// 8. CREAR CLASE PERSONALIZADA PARA ENCABEZADO Y PIE DE PÁGINA
class MYPDF extends TCPDF {
    public function Header() {
        // URL de las imágenes
        $logo_left  = 'https://iestphuanta.edu.pe/wp-content/uploads/2021/12/logo_tecno-1-2.png';
        $logo_right = 'https://dreayacucho.gob.pe/storage/directory/lCcjIpyYl7E5tQjWegZVLZvp1ZIMbY-metaWk9PRUEybXNRUGlYWWtKRng0SkxqcG9SRW5jTEZuLW1ldGFiRzluYnk1d2JtYz0tLndlYnA=-.webp';

        // Logo izquierdo
        $this->Image($logo_left, 15, 10, 25);  
        // Logo derecho
        $this->Image($logo_right, 170, 10, 25); 

        // Título principal 
        $this->SetXY(55, 12); // desplazado al centro 
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(0, 70, 140); // Azul fuerte
        $this->Cell(100, 6, 'INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO', 0, 1, 'C');

        // Subtítulo
        $this->SetX(55);
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(0, 70, 140); // Gris oscuro
        $this->Cell(100, 6, '"HUANTA"', 0, 1, 'C');

         // contenido
        $this->SetX(55, 12);
        $this->SetFont('helvetica', 'I', 10);
        $this->SetTextColor(85, 85, 85); // Gris oscuro
        $this->Cell(100, 6, 'Sistema de Control Patrimonial', 0, 1, 'C');

        // Línea decorativa azul
        $this->SetDrawColor(52, 152, 219);
        $this->SetLineWidth(0.8);
        $this->Line(15, 44, 195, 44);

        $this->Ln(5); // Espacio adicional
    }

  public function Footer() {
    // Posicionar a 15 mm del final de la página
    $this->SetY(-15);

    // Línea superior del footer
    $this->SetDrawColor(189, 195, 199);
    $this->SetLineWidth(0.5);
    $this->Line(15, $this->GetY() - 5, 195, $this->GetY() - 5);

    // Estilo del texto
    $this->SetFont('helvetica', 'I', 8);
    $this->SetTextColor(100, 100, 100);

    // Texto de número de página centrado
    $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');

    // Reset de estilo
    $this->SetTextColor(0, 0, 0);
    $this->SetLineWidth(0.2);

    }
}


$pdf = new MYPDF();
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Marycielo');
$pdf->SetTitle('Reporte de bienes');

// 10. CONFIGURAR MÁRGENES Y PÁGINA
$pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT); 
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);                
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  

// Configurar salto de página automático
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Configurar fuente por defecto
$pdf->SetFont('helvetica', '', 8);

// Agregar nueva página
$pdf->AddPage();

// 11. INSERTAR CONTENIDO HTML EN EL PDF
// Convertir HTML a PDF y renderizarlo
$pdf->writeHTML($contenido_pdf, true, false, true, false, '');

// 12. GENERAR Y MOSTRAR EL PDF
// Generar archivo PDF con nombre único (incluye fecha y hora)
$pdf->Output('reporte_bienes_' . date('Ymd_His') . '.pdf', 'I');
    }
