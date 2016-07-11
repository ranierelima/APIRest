<?php 

$conecta = mysqli_connect('localhost', 'root', '', 'apirestphp');

function listarJson($con){
    $produtos = array();
    $resultado = mysqli_query($con, 'select * from produtos');
    while($produto_banco = mysqli_fetch_assoc($resultado)){
        $produto = array(
        'id'=>$produto_banco['id'],
        'nome'=>$produto_banco['nome'],
        'preco'=>$produto_banco['preco'],
        'descricao'=>$produto_banco['descricao']
        );
        array_push($produtos, $produto);
    }
    header('Content-Type: application/json; charset=UTF-8');
    return $produtos;    
}

function listarJsonPorID($con, $id){
    $produtos = array();
    $resultado = mysqli_query($con, "select * from produtos where id='{$id}'");
    while($produto_banco = mysqli_fetch_assoc($resultado)){
        $produto = array(
        'id'=>$produto_banco['id'],
        'nome'=>$produto_banco['nome'],
        'preco'=>$produto_banco['preco'],
        'descricao'=>$produto_banco['descricao']
        );
        array_push($produtos, $produto);
    }
    header('Content-Type: application/json; charset=UTF-8');
    return $produtos;    
}

function listarXml($con){
$dom = new DOMDocument("1.0", "ISO-8859-1");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$root = $dom->createElement("produtos");

$resultado = mysqli_query($con, 'select * from produtos');
while($produto_banco = mysqli_fetch_assoc($resultado)){
    
$produto = $dom->createElement("produto");

$id = $dom->createElement("id", $produto_banco['id']);
$nome = $dom->createElement("nome", $produto_banco['nome']);
$preco = $dom->createElement("preco", $produto_banco['preco']);
$descricao = $dom->createElement("descricao", $produto_banco['descricao']);

$produto->appendChild($id);
$produto->appendChild($nome);
$produto->appendChild($preco);
$produto->appendChild($descricao);

$root->appendChild($produto);
}
    
$dom->appendChild($root);

header("Content-Type: text/xml");
print $dom->saveXML();
}


function listarXmlPorID($con, $id){
$dom = new DOMDocument("1.0", "ISO-8859-1");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$root = $dom->createElement("produtos");

$resultado = mysqli_query($con, "select * from produtos where id='{$id}'");
while($produto_banco = mysqli_fetch_assoc($resultado)){
    
$produto = $dom->createElement("produto");

$id = $dom->createElement("id", $produto_banco['id']);
$nome = $dom->createElement("nome", $produto_banco['nome']);
$preco = $dom->createElement("preco", $produto_banco['preco']);
$descricao = $dom->createElement("descricao", $produto_banco['descricao']);

$produto->appendChild($id);
$produto->appendChild($nome);
$produto->appendChild($preco);
$produto->appendChild($descricao);

$root->appendChild($produto);
}
    
$dom->appendChild($root);

header("Content-Type: text/xml");
print $dom->saveXML();
}

if(isset($_GET['formato'])){
    switch ($_GET['formato']){

        case "xml":
            if(isset($_GET['id'])){
                listarXmlPorId($conecta , $_GET['id']);
            }else listarXml($conecta);
            break;
        case "json":
            if(isset($_GET['id'])){
                $contatos = listarJsonPorID($conecta,$_GET['id']);
            }else $contatos = listarJson($conecta);
            echo json_encode($contatos);
            break;
        default: echo "Formato não aceito, desculpe pelo transtorno.";
   }
}else{
    echo "Formato não informado";
}
