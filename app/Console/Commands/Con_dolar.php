<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conexao\_Conexao;
use SoapClient;

class Con_dolar extends Command
{
    
    protected $signature = 'Con_dolar';
    protected $description = 'Executa consulta do dólar';
    
    public static function gravar($paran){
        
        try {
            echo date("d/m/Y H:i:s").' - Gravando Dólar' . "\n";
            $con = new _Conexao();
        
            $sql = "
                UPDATE OR INSERT INTO TBDOLAR_MENSAL (ANO, MES, DOLAR_COMPRA, DOLAR_VENDA)
                              VALUES (:ANO, :MES, :DOLAR_COMPRA, :DOLAR_VENDA)
                            MATCHING (ANO, MES);
            ";	

            $args = [
                ':ANO'          => $paran['ANO'],
                ':MES'          => $paran['MES'],
                ':DOLAR_VENDA'  => $paran['DOLAR_VENDA'],
                ':DOLAR_COMPRA' => $paran['DOLAR_COMPRA'],
            ];

            $con->execute($sql,$args);
            $con->commit();
            $con->close();
            $con = null;

            echo date("d/m/Y H:i:s").' - Dólar Gravado' . "\n";

        } catch (Exception $exc) {

            try {
                $con->rollback();
                $con->close();
                $con = null;
            } catch (Exception $ex) {}

            echo date("d/m/Y H:i:s").' - Erro ao Gravando Dólar' . "\n";
            echo $exc->getTraceAsString();
            echo '';
        }

    }
    
    /**
     * Codigo executado
     */
    public function handle()
    {  

        try {

            ini_set('default_socket_timeout' , 300);
            ini_set("soap.wsdl_cache_enabled", "0");

            header('Content-type: text/html; charset=UTF-8');
            //$ParametroPesquisa = "getValoresSeriesXML";
            
            $WsSOAP = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl");

            try {
                $ResultadoPesquisaWS = $WsSOAP->getValoresSeriesXML([1,10813] , date("d/m/Y"), date("d/m/Y"));
             
                if (isset($ResultadoPesquisaWS)) {

                    $CotacaoMoedaWS  = simplexml_load_string($ResultadoPesquisaWS);
                    
                    $paran = array('DOLAR_VENDA' => 0, 'DOLAR_COMPRA' => 0, 'ANO'=> date("Y") , 'MES' => date("m"));

                    $I = 0;
                    foreach ($CotacaoMoedaWS as $key => $items) {

                        $ValorMoeda      = $items->ITEM->VALOR;
                        $Data            = $items->ITEM->DATA;
                        $I++;

                        if($I == 1){
                            $paran['DOLAR_VENDA']  = $ValorMoeda;
                            echo "Valor Venda R$ $ValorMoeda Dia da Cotação: $Data"."\n";
                        }else{
                            $paran['DOLAR_COMPRA'] = $ValorMoeda;
                            echo "Valor Compra R$ $ValorMoeda Dia da Cotação: $Data"."\n";
                        }
                        
                    }

                    $this->gravar($paran);

                } else {
                    exit('Falha ao abrir XML do BCB.');
                }

            } catch (Exception $Exception) {
                echo "ERRO AO REALIZAR A CAPTURA DE DADOS DO WEBSERVICE: " . $Exception -> getMessage();
            }

        } catch (Exception $exc) {
            echo date("d/m/Y H:i:s").' - Erro ao executar Con_dolar:'.$value->ID.' ('.$exc->getTraceAsString().')';
        }
			
    }
     
}

