<?php
include "../config/protege.php";
include "../config/conexao.php";

$id = (int) ($_GET["id"] ?? 0);

$sql = "SELECT id, nome FROM equipamentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$equipamento = $resultado->fetch_assoc();

if (!$equipamento) {
    echo "Equipamento não encontrado.";
    exit;
}

$urlEquipamento = "https://cafedatarde.online/inventario-clinico/paginas/visualizar.php?id="
    . $equipamento["id"];

$urlQr = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data="
    . urlencode($urlEquipamento);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta - MedInventory</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eeeeee;
            margin: 0;
            padding: 30px;
        }

        .acoes {
            text-align: center;
            margin-bottom: 20px;
        }

        .acoes button {
            padding: 10px 18px;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            font-size: 14px;
        }

        .imprimir {
            background: #0d6efd;
            color: white;
        }

        .voltar {
            background: #6c757d;
            color: white;
        }

        .etiqueta {
            width: 80mm;
            min-height: 60mm;
            margin: auto;
            background: white;
            border: 2px solid #000;
            padding: 6mm;
            text-align: center;
            box-sizing: border-box;
        }

        .titulo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .patrimonio {
            font-size: 20px;
            font-weight: bold;
            margin: 8px 0;
        }

        .nome {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .qr img {
            width: 38mm;
            height: 38mm;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .acoes {
                display: none;
            }

            .etiqueta {
                margin: 0;
            }

            @page {
                margin: 0;
            }
        }
    </style>
</head>

<body>

<div class="acoes">

    <button
        class="imprimir"
        onclick="window.print()"
    >
        Imprimir etiqueta
    </button>

    <button
        class="voltar"
        onclick="history.back()"
    >
        Voltar
    </button>

</div>

<div class="etiqueta">

    <div class="titulo">
        MedInventory
    </div>

    <div class="qr">
        <img
            src="<?php echo htmlspecialchars($urlQr); ?>"
            alt="QR Code"
        >
    </div>

    <div class="patrimonio">
        Patrimônio
        <?php
        echo str_pad(
            $equipamento["id"],
            4,
            "0",
            STR_PAD_LEFT
        );
        ?>
    </div>

    <div class="nome">
        <?php
        echo htmlspecialchars(
            mb_convert_case(
                $equipamento["nome"],
                MB_CASE_TITLE,
                "UTF-8"
            )
        );
        ?>
    </div>

</div>

</body>
</html>