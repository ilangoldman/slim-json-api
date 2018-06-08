<?php

class Price {

    function calcularRating() {
        return 1;
    }

    function calcularTaxa($rating) {
        var_export($rating);
        $taxa = array (
            0 => 0.01,
            1 => 0.03
        );
        return $taxa[$rating];
    }

    function calcularParcela($valor, $parcelas, $juros){
        $juros = $juros * 100;
        $juros = bcdiv($juros,100,15);
        $e = 1.0;
        $cont = 1.0;

        for($k=1;$k<=$parcelas;$k++){
            $cont = bcmul($cont,bcadd($juros,1,15),15);
            $e = bcadd($e, $cont, 15);
        }
        $e = bcsub($e, $cont, 15);
        $valor = bcmul($valor, $cont, 15);
        return bcdiv($valor, $e, 15);
    }

    function calcularJuros($saldoDevedor, $taxa) {
        return $saldoDevedor * $taxa;
    }

    function calcularPrincipal($parcela,$juros) {
        return $parcela - $juros;
    }

    function calcularMulta($parcela, $atraso) {
        return 0;
    }

    function calcularIR($rendimentos) {
        return 0;
    }
}