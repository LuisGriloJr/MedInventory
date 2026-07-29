<?php
include "../config/protege.php";
include "../config/conexao.php";

$id = (int) ($_GET["id"] ?? 0);

$sql = "
    SELECT
        manutencoes.*,
        equipamentos.id AS equipamento_id,
        equipamentos.nome AS equipamento_nome,
        equipamentos.fabricante,
        equipamentos.modelo,
        equipamentos.numero_serie,
        equipamentos.setor,
        equipamentos.localizacao,
        empresas.nome AS empresa_nome,
        empresas.telefone AS empresa_telefone,
        usuarios.nome AS usuario_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON equipamentos.id = manutencoes.equipamento_id
    INNER JOIN empresas
        ON empresas.id = manutencoes.empresa_id
    INNER JOIN usuarios
        ON usuarios.id = manutencoes.usuario_id
    WHERE manutencoes.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$manutencao = $resultado->fetch_assoc();

if (!$manutencao) {
    echo "Manutenção não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ordem de Serviço</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            background: #eee;
        }

        .pagina {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
            background: white;
        }

        h1, h2, p {
            margin-top: 0;
        }

        .cabecalho {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .cabecalho h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .secao {
            margin-top: 18px;
        }

        .secao h2 {
            font-size: 15px;
            background: #eee;
            border: 1px solid #000;
            padding: 7px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 22%;
            background: #f7f7f7;
        }

        .campo-linhas {
            min-height: 80px;
            border: 1px solid #000;
            padding: 10px;
            white-space: pre-wrap;
        }

        .linha-preenchimento {
            display: inline-block;
            min-width: 250px;
            height: 25px;
            border-bottom: 1px solid #000;
        }

        .assinaturas {
            display: flex;
            gap: 40px;
            margin-top: 70px;
        }

        .assinatura {
            flex: 1;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 7px;
            font-size: 13px;
        }

        .acoes {
            width: 210mm;
            margin: 20px auto;
            display: flex;
            gap: 10px;
        }

        .botao {
            text-decoration: none;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            background: #0d6efd;
            color: white;
            border-radius: 5px;
        }

        .botao-voltar {
            background: #6c757d;
        }

        @media print {
            body {
                background: white;
            }

            .pagina {
                margin: 0;
                width: 100%;
                min-height: auto;
                padding: 10mm;
            }

            .acoes {
                display: none;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<body>

<div class="acoes">
    <button class="botao" onclick="window.print()">
        Imprimir
    </button>

    <a
        href="visualizar_manutencao.php?id=<?php echo $manutencao["id"]; ?>"
        class="botao botao-voltar"
    >
        Voltar
    </a>
</div>

<div class="pagina">

    <div class="cabecalho">
        <h1>ORDEM DE SERVIÇO DE MANUTENÇÃO</h1>
        <p>MedInventory — Controle de Equipamentos Clínicos</p>
    </div>

    <table>
        <tr>
            <th>Registro da manutenção</th>
            <td>
                <?php echo str_pad($manutencao["id"], 5, "0", STR_PAD_LEFT); ?>
            </td>

            <th>Data de abertura</th>
            <td>
                <?php echo date("d/m/Y", strtotime($manutencao["data_abertura"])); ?>
            </td>
        </tr>

        <tr>
            <th>Número da OS externa</th>
            <td>
                <?php echo htmlspecialchars($manutencao["numero_os"] ?: "-"); ?>
            </td>

            <th>Tipo</th>
            <td><?php echo htmlspecialchars($manutencao["tipo"]); ?></td>
        </tr>
    </table>

    <div class="secao">
        <h2>DADOS DO EQUIPAMENTO</h2>

        <table>
            <tr>
                <th>Patrimônio</th>
                <td>
                    <?php
                    echo str_pad(
                        $manutencao["equipamento_id"],
                        4,
                        "0",
                        STR_PAD_LEFT
                    );
                    ?>
                </td>

                <th>Equipamento</th>
                <td><?php echo htmlspecialchars($manutencao["equipamento_nome"]); ?></td>
            </tr>

            <tr>
                <th>Fabricante</th>
                <td><?php echo htmlspecialchars($manutencao["fabricante"]); ?></td>

                <th>Modelo</th>
                <td><?php echo htmlspecialchars($manutencao["modelo"]); ?></td>
            </tr>

            <tr>
                <th>Número de série</th>
                <td><?php echo htmlspecialchars($manutencao["numero_serie"]); ?></td>

                <th>Setor</th>
                <td><?php echo htmlspecialchars($manutencao["setor"]); ?></td>
            </tr>

            <tr>
                <th>Localização</th>
                <td colspan="3">
                    <?php echo htmlspecialchars($manutencao["localizacao"] ?: "-"); ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="secao">
        <h2>EMPRESA RESPONSÁVEL</h2>

        <table>
            <tr>
                <th>Empresa</th>
                <td><?php echo htmlspecialchars($manutencao["empresa_nome"]); ?></td>

                <th>Telefone</th>
                <td><?php echo htmlspecialchars($manutencao["empresa_telefone"] ?: "-"); ?></td>
            </tr>
        </table>
    </div>

    <div class="secao">
        <h2>DESCRIÇÃO DO PROBLEMA</h2>

        <div class="campo-linhas"><?php
            echo htmlspecialchars($manutencao["descricao_problema"]);
        ?></div>
    </div>

    <div class="secao">
        <h2>DADOS DA RETIRADA</h2>

        <table>
            <tr>
                <th>Nome de quem retirou</th>
                <td colspan="3">&nbsp;</td>
            </tr>

            <tr>
                <th>Documento</th>
                <td>&nbsp;</td>

                <th>Telefone</th>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <th>Data da retirada</th>
                <td>&nbsp;</td>

                <th>Hora</th>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <th>Placa do veículo</th>
                <td>&nbsp;</td>

                <th>Observações</th>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

    <div class="secao">
        <h2>DECLARAÇÃO</h2>

        <p style="font-size: 13px; line-height: 1.5;">
            Declaro que recebi o equipamento identificado nesta ordem de serviço
            para encaminhamento à empresa responsável pela manutenção, nas
            condições descritas neste documento.
        </p>
    </div>

    <div class="assinaturas">
        <div class="assinatura">
            Assinatura de quem entrega
        </div>

        <div class="assinatura">
            Assinatura de quem retira
        </div>
    </div>

</div>

</body>
</html>