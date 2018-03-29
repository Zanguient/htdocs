        
<?php
    if ( isset($_GET['objeto']) ) {
        $objeto = $_GET['objeto'];
    } else {
        $objeto = '';
    }
    
    $title = (($objeto != '') ? $objeto . ' - ' : '') . "Rastreamento de Objetos Correios";
?>

<html>
    <head>
        <!-- Developed by Emerson Coelho-->
        <title>
            <?php echo $title; ?>
        </title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <style>
        .container {
            position: relative;
        }
        .container .powerdby {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 20px;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Informe o código do objeto:</h2>
            <label>Busque de forma rápida seus objetos / encomendas</label>
            <form action="" method="get" class="form-inline">


            <div class="input-group">
                <span class="input-group-addon">Objeto:</span>
                <input id="obj" type="text" class="form-control" name="objeto" placeholder="Informe o código do objeto"  value="<?php echo $objeto  ?>" />
            </div>
            <button type="submit" class="btn btn-success">Buscar</button>
            </form>

        <?php
        
        
            if ( $objeto != '' ) {
                
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,"http://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,
                            "Objetos=$objeto");

                // in real life you should use something like:
                // curl_setopt($ch, CURLOPT_POSTFIELDS, 
                //          http_build_query(array('postvar1' => 'value1')));

                // receive server response ...
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $server_output = curl_exec ($ch);
                
                print_r('<div id="resultado">' . utf8_encode($server_output) . '</div>');

                curl_close ($ch);
                   
            }
        ?>
            
        </div>
        <script>
            var dados = $('#resultado');
            dados.find('style').remove();
            dados.find('a').remove();
            dados.find('.tituloimagem').remove();
            dados.find('table').addClass('table table-striped table-condensed');
        </script>
    </body>
</html>
