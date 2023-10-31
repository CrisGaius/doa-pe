<?php 
    function formatar_conta($conta) {
        $primeira_parte = substr($conta, 0, 7);
        $ultimo_digito = substr($conta, -1);
        
        return "$primeira_parte-$ultimo_digito";
    }
?>