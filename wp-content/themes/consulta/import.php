<?php 

# esse script vai ser rodado uma única vez para importar os usuários convidados
include('../../../wp-load.php');

ini_set('max_execution_time', 16000);

if (!current_user_can('manage_options')) die('faltando um carimbo na segunda via da autorização');

$separator = '|';

$csv = fopen('../../../../doc/usuarios.csv', 'r');

$cc = 0; 
while(!feof($csv)){
    $line = fgets($csv);
    $fields = explode($separator, $line);
    
    
    if ($header) {
        
        $header = false;
        continue;
        
    }
    
    $nome = trim($fields[0]);
    $email = trim($fields[1]);
    $cat = trim($fields[2]);
    $subcat = trim($fields[3]);
    $indicacao = trim($fields[4]);
    $estado = trim($fields[5]);
    $ocupacao = trim($fields[6]);
    $pass = trim($fields[7]);
    
    $user_nicename = sanitize_title( $nome );
    
    $new = array(
    
        'display_name' => $nome,
        'user_login' => $email,
        'user_email' => $email,
        'user_pass' => $pass,
        'user_nicename' => $user_nicename
    
    );
    
    $newid = wp_insert_user($new);
    
    if ($newid && !is_wp_error($newid)) {
    
        echo $nome, '<br />';
        update_user_meta($newid, 'estado', $estado);
        update_user_meta($newid, 'categoria', $cat);
        update_user_meta($newid, 'sub_categoria', $subcat);
        update_user_meta($newid, 'ocupacao', 'outra');
        update_user_meta($newid, 'ocupacao_outra', $ocupacao);
        update_user_meta($newid, 'indicacao', $indicacao);
        update_user_meta($newid, 'first_name', $nome);
        update_user_meta($newid, 'atuacao', 'outra_area_cultura');
    
    } else {
    
        echo 'ERRO: ', $nome;
        var_dump($newid);
        echo '<br />';
    
    }
    
    $cc++;
    
    

}

echo 'Dados de CSV carregados com sucesso.';

fclose($csv);
