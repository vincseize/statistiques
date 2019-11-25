<style>
    body,html {
        margin:0;
        padding:0;
        font-family: Arial;
    }
    .resultats_database{
        display: block;
        background-color: yellow;
        font-size: 10px;
    }
</style>

<div class="resultats_database">
    <?php
        include('inc/insert_donnees.php');
    ?>
</div>
<br>

<form>
<input type="button" value="NOUVEAUX ELEVES ALEATOIRES (avec ecoles/sports)" onclick="window.location.href='index.php'" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="RESET" onclick="window.location.href='index.php?VAR_VIDER'" />
</form> 


<div class="resultats"></div>
