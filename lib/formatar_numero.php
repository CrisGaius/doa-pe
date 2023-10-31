<?php 
    function formatar_numero($contato) {
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '$1 $2-$3', $contato);
    }
?>